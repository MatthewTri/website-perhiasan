<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\ProductSpecification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use SimpleXMLElement;

class ProductImportService
{
    /**
     * Import an uploaded product file and return a summary payload.
     */
    public function importUploadedFile(UploadedFile $file): array
    {
        $path = $file->getRealPath();

        if ($path === false || $path === '') {
            throw new RuntimeException('Uploaded file could not be read.');
        }

        $rows = $this->readRows($path, strtolower($file->getClientOriginalExtension()));

        $summary = [
            'total' => count($rows),
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        foreach ($rows as $index => $row) {
            $lineNumber = $index + 2;

            try {
                $action = $this->processRow($row);

                if ($action === 'created') {
                    $summary['created']++;
                } else {
                    $summary['updated']++;
                }
            } catch (\Throwable $e) {
                $summary['skipped']++;
                $name = $this->clean($row['name'] ?? $row['Nama'] ?? $row['product_name'] ?? '');
                $message = 'Row ' . $lineNumber . ($name !== '' ? ' (' . $name . ')' : '') . ': ' . $e->getMessage();

                $summary['errors'][] = $message;
                Log::warning('Product bulk import skipped: ' . $message);
            }
        }

        return $summary;
    }

    /**
     * Read rows from CSV, TSV, or XLSX.
     */
    private function readRows(string $path, string $extension): array
    {
        return match ($extension) {
            'csv' => $this->readDelimitedFile($path, ','),
            'tsv' => $this->readDelimitedFile($path, "\t"),
            'txt' => $this->readDelimitedFile($path, $this->detectDelimiter($path)),
            'xlsx' => $this->readXlsxFile($path),
            default => throw new RuntimeException('Unsupported file type. Use CSV or XLSX.'),
        };
    }

    private function detectDelimiter(string $path): string
    {
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return ',';
        }

        $sample = (string) fgets($handle);
        fclose($handle);

        return str_contains($sample, "\t") ? "\t" : ',';
    }

    private function readDelimitedFile(string $path, string $delimiter): array
    {
        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException('Unable to open file: ' . $path);
        }

        $header = fgetcsv($handle, 0, $delimiter);

        if ($header === false) {
            fclose($handle);
            return [];
        }

        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header[0]);
        $header = array_map(fn ($value) => trim((string) $value), $header);

        $rows = [];

        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (count($data) === 1 && trim((string) $data[0]) === '') {
                continue;
            }

            $values = array_slice($data, 0, count($header));
            $values = array_pad($values, count($header), null);

            $rows[] = $this->normalizeRow(array_combine($header, $values) ?: []);
        }

        fclose($handle);

        return $rows;
    }

    private function readXlsxFile(string $path): array
    {
        $entries = $this->readZipEntries($path);

        $workbook = $this->parseXmlEntry($entries, 'xl/workbook.xml');
        $relations = $this->parseWorkbookRelations($entries);
        $sheetPath = $this->resolveFirstSheetPath($workbook, $relations);

        if ($sheetPath === null) {
            return [];
        }

        $sharedStrings = $this->parseSharedStrings($entries);
        $worksheet = $this->parseXmlEntry($entries, $sheetPath);

        return $this->worksheetToRows($worksheet, $sharedStrings);
    }

    private function readZipEntries(string $path): array
    {
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new RuntimeException('Unable to open XLSX file: ' . $path);
        }

        $stats = fstat($handle);
        $size = (int) ($stats['size'] ?? 0);

        if ($size <= 0) {
            fclose($handle);
            throw new RuntimeException('XLSX file is empty.');
        }

        $searchLength = min($size, 66000);
        fseek($handle, $size - $searchLength);
        $tail = fread($handle, $searchLength);

        if ($tail === false) {
            fclose($handle);
            throw new RuntimeException('Unable to inspect XLSX archive.');
        }

        $eocdPosition = strrpos($tail, "PK\x05\x06");

        if ($eocdPosition === false) {
            fclose($handle);
            throw new RuntimeException('Invalid XLSX archive.');
        }

        $eocd = substr($tail, $eocdPosition, 22);
        $eocdData = unpack('vdisk/vdiskStart/ventriesDisk/ventriesTotal/VdirectorySize/VdirectoryOffset/vcommentLength', substr($eocd, 4, 18));
        $directoryOffset = (int) ($eocdData['directoryOffset'] ?? 0);
        $directorySize = (int) ($eocdData['directorySize'] ?? 0);

        fseek($handle, $directoryOffset);
        $directoryData = fread($handle, $directorySize);

        if ($directoryData === false) {
            fclose($handle);
            throw new RuntimeException('Unable to read XLSX central directory.');
        }

        $entries = [];
        $offset = 0;

        while ($offset < strlen($directoryData)) {
            $signature = substr($directoryData, $offset, 4);

            if ($signature !== "PK\x01\x02") {
                break;
            }

            $header = substr($directoryData, $offset, 46);
            $fields = unpack(
                'Vsignature/vversionMade/vversionNeeded/vflags/vmethod/vtime/vdate/Vcrc/VcompressedSize/VuncompressedSize/vnameLength/vextraLength/vcommentLength/vdisk/vinternal/Vexternal/VlocalOffset',
                $header
            );

            $offset += 46;
            $name = substr($directoryData, $offset, (int) $fields['nameLength']);
            $offset += (int) $fields['nameLength'];
            $offset += (int) $fields['extraLength'];
            $offset += (int) $fields['commentLength'];

            $entries[trim($name)] = [
                'method' => (int) $fields['method'],
                'compressedSize' => (int) $fields['compressedSize'],
                'localOffset' => (int) $fields['localOffset'],
            ];
        }

        fclose($handle);

        return $this->loadZipEntryContents($path, $entries);
    }

    private function loadZipEntryContents(string $path, array $entries): array
    {
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new RuntimeException('Unable to open XLSX file: ' . $path);
        }

        $contents = [];

        foreach ($entries as $name => $entry) {
            fseek($handle, $entry['localOffset']);
            $localHeader = fread($handle, 30);

            if ($localHeader === false || substr($localHeader, 0, 4) !== "PK\x03\x04") {
                continue;
            }

            $localFields = unpack('Vsignature/vversion/vflags/vmethod/vtime/vdate/Vcrc/VcompressedSize/VuncompressedSize/vnameLength/vextraLength', $localHeader);
            $nameLength = (int) ($localFields['nameLength'] ?? 0);
            $extraLength = (int) ($localFields['extraLength'] ?? 0);

            fseek($handle, $nameLength + $extraLength, SEEK_CUR);
            $raw = fread($handle, $entry['compressedSize']);

            if ($raw === false) {
                continue;
            }

            $contents[$name] = match ($entry['method']) {
                0 => $raw,
                8 => gzinflate($raw),
                default => null,
            };
        }

        fclose($handle);

        return array_filter($contents, fn ($value) => is_string($value) && $value !== '');
    }

    private function parseXmlEntry(array $entries, string $path): ?SimpleXMLElement
    {
        $xml = $entries[$path] ?? null;

        if (!is_string($xml) || trim($xml) === '') {
            return null;
        }

        $parsed = simplexml_load_string($xml, SimpleXMLElement::class, LIBXML_NONET | LIBXML_NOCDATA);

        if ($parsed === false) {
            throw new RuntimeException('Unable to parse XLSX XML entry: ' . $path);
        }

        return $parsed;
    }

    private function parseWorkbookRelations(array $entries): array
    {
        $relations = $this->parseXmlEntry($entries, 'xl/_rels/workbook.xml.rels');

        if ($relations === null) {
            return [];
        }

        $map = [];

        foreach ($relations->xpath('//*[local-name()="Relationship"]') ?: [] as $relationship) {
            $attributes = $relationship->attributes();
            $id = (string) ($attributes['Id'] ?? '');
            $target = (string) ($attributes['Target'] ?? '');

            if ($id !== '' && $target !== '') {
                $normalizedTarget = ltrim($target, '/');

                if (!str_starts_with($normalizedTarget, 'xl/')) {
                    $normalizedTarget = 'xl/' . $normalizedTarget;
                }

                $map[$id] = $normalizedTarget;
            }
        }

        return $map;
    }

    private function resolveFirstSheetPath(?SimpleXMLElement $workbook, array $relations): ?string
    {
        if ($workbook === null) {
            return null;
        }

        $sheets = $workbook->xpath('//*[local-name()="sheets"]/*[local-name()="sheet"]') ?: [];
        $firstSheet = $sheets[0] ?? null;

        if ($firstSheet === null) {
            return null;
        }

        // r:id is in the relationships namespace, not the default namespace,
        // so ->attributes() alone won't see it. Query the namespace directly.
        $relNamespace = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';
        $relAttributes = $firstSheet->attributes($relNamespace);
        $relationshipId = isset($relAttributes['id']) ? (string) $relAttributes['id'] : '';

        if ($relationshipId === '' || !isset($relations[$relationshipId])) {
            return null;
        }

        return $relations[$relationshipId];
    }

    private function parseSharedStrings(array $entries): array
    {
        $sharedStringsXml = $this->parseXmlEntry($entries, 'xl/sharedStrings.xml');

        if ($sharedStringsXml === null) {
            return [];
        }

        $strings = [];

        foreach ($sharedStringsXml->xpath('//*[local-name()="si"]') ?: [] as $node) {
            $strings[] = trim($this->extractText($node));
        }

        return $strings;
    }

    private function worksheetToRows(SimpleXMLElement $worksheet, array $sharedStrings): array
    {
        $rows = [];

        foreach ($worksheet->xpath('//*[local-name()="sheetData"]/*[local-name()="row"]') ?: [] as $rowNode) {
            $row = [];

            foreach ($rowNode->xpath('./*[local-name()="c"]') ?: [] as $cell) {
                $attributes = $cell->attributes();
                $reference = (string) ($attributes['r'] ?? '');

                if ($reference === '') {
                    continue;
                }

                $row[$this->columnIndexFromReference($reference)] = $this->resolveCellValue($cell, $sharedStrings);
            }

            if (!empty($row)) {
                ksort($row);
                $rows[] = $row;
            }
        }

        if (empty($rows)) {
            return [];
        }

        $headers = [];
        $normalized = [];

        foreach ($rows as $index => $row) {
            $maxColumn = $row === [] ? 0 : max(array_keys($row));
            $orderedValues = [];

            for ($column = 1; $column <= $maxColumn; $column++) {
                $orderedValues[] = $row[$column] ?? '';
            }

            if ($index === 0) {
                $headers = array_map(fn ($value) => $this->clean($value), $orderedValues);
                continue;
            }

            $assoc = [];

            foreach ($headers as $headerIndex => $header) {
                if ($header === '') {
                    continue;
                }

                $assoc[$header] = $orderedValues[$headerIndex] ?? null;
            }

            $normalized[] = $this->normalizeRow($assoc);
        }

        return $normalized;
    }

    private function columnIndexFromReference(string $reference): int
    {
        preg_match('/^([A-Z]+)/i', $reference, $matches);
        $columnLetters = strtoupper($matches[1] ?? 'A');
        $index = 0;

        foreach (str_split($columnLetters) as $letter) {
            $index = ($index * 26) + (ord($letter) - 64);
        }

        return max(1, $index);
    }

    private function resolveCellValue(SimpleXMLElement $cell, array $sharedStrings): string
    {
        $attributes = $cell->attributes();
        $type = (string) ($attributes['t'] ?? '');

        if ($type === 's') {
            $value = (int) ((string) ($cell->v ?? '0'));

            return $this->clean($sharedStrings[$value] ?? '');
        }

        if ($type === 'inlineStr') {
            return $this->clean($this->extractText($cell));
        }

        if ($type === 'b') {
            return ((string) ($cell->v ?? '0')) === '1' ? '1' : '0';
        }

        return $this->clean((string) ($cell->v ?? ''));
    }

    private function extractText(SimpleXMLElement $node): string
    {
        $text = '';

        foreach ($node->xpath('.//*[local-name()="t"]') ?: [] as $textNode) {
            $text .= (string) $textNode;
        }

        if ($text === '') {
            $text = (string) $node;
        }

        return $this->clean($text);
    }

    private function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $normalized[$this->normalizeHeader($key)] = $value;
        }

        return $normalized;
    }

    private function normalizeHeader(string $header): string
    {
        $normalized = Str::of($header)->trim()->lower()->replace([' ', '-'], '_')->value();

        return match ($normalized) {
            'nama', 'name', 'product_name', 'nama_produk' => 'name',
            'deskripsi', 'description' => 'description',
            'harga_idr', 'price_idr', 'idr_price', 'price_in_idr' => 'price_idr',
            'harga_usd', 'price_usd', 'usd_price', 'price_in_usd' => 'price_usd',
            'slug' => 'slug',
            'parent_kategori', 'parent_category', 'parent_category_name', 'collection', 'collection_parent' => 'parent_category',
            'kategori', 'category', 'sub_category', 'subcategory' => 'category',
            'photos', 'photo', 'images', 'image' => 'images',
            'colors', 'colour', 'color' => 'colors',
            'sizes', 'size', 'size_options' => 'sizes',
            'specifications', 'specification', 'specs', 'details' => 'specifications',
            default => $normalized,
        };
    }

    private function processRow(array $row): string
    {
        return DB::transaction(function () use ($row) {
            $name = $this->clean($row['name'] ?? '');
            $slug = $this->clean($row['slug'] ?? '');

            if ($name === '') {
                throw new RuntimeException('Missing product name.');
            }

            $categoryId = $this->resolveCategory(
                $this->clean($row['parent_category'] ?? ''),
                $this->clean($row['category'] ?? '')
            );

            $product = null;

            if ($slug !== '') {
                $product = Product::where('slug', $slug)->lockForUpdate()->first();
            }

            if ($product === null) {
                $product = Product::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
                    ->lockForUpdate()
                    ->first();
            }

            $payload = [
                'name' => $name,
                'description' => $this->nullIfEmpty($row['description'] ?? null),
                'price_idr' => $this->parseMoney($row['price_idr'] ?? 0),
                'price_usd' => $this->parseMoney($row['price_usd'] ?? 0),
                'category_id' => $categoryId,
            ];

            if ($product) {
                $product->update($payload + ($slug !== '' ? ['slug' => $slug] : []));
                $action = 'updated';
            } else {
                $product = Product::create($payload + [
                    'slug' => $slug !== '' ? $this->makeUniqueSlug($slug) : $this->makeUniqueSlug($name),
                    'rating_avg' => 0,
                    'rating_count' => 0,
                ]);
                $action = 'created';
            }

            $this->syncImages($product->id, $row['images'] ?? null);
            $this->syncColors($product->id, $row['colors'] ?? null);
            $this->syncSizes($product->id, $row['sizes'] ?? null);
            $this->syncSpecifications($product->id, $row['specifications'] ?? null);

            return $action;
        });
    }

    private function resolveCategory(string $parentName, string $childName): int
    {
        if ($parentName === '' && $childName === '') {
            throw new RuntimeException('Category fields are required.');
        }

        if ($parentName === '' && $childName !== '') {
            $root = Category::firstOrCreate(
                ['slug' => Str::slug($childName)],
                ['name' => $childName, 'parent_id' => null]
            );

            return $root->id;
        }

        $parent = Category::firstOrCreate(
            ['slug' => Str::slug($parentName)],
            ['name' => $parentName, 'parent_id' => null]
        );

        if ($childName === '') {
            return $parent->id;
        }

        $child = Category::firstOrCreate(
            ['slug' => Str::slug($childName)],
            ['name' => $childName, 'parent_id' => $parent->id]
        );

        if ($child->parent_id !== $parent->id) {
            $child->update(['parent_id' => $parent->id]);
        }

        return $child->id;
    }

    private function syncImages(int $productId, mixed $imagesCell): void
    {
        $raw = $this->clean($imagesCell ?? '');

        if ($raw === '') {
            return;
        }

        if (mb_strtolower($raw) === 'none') {
            ProductImage::where('product_id', $productId)->delete();
            return;
        }

        ProductImage::where('product_id', $productId)->delete();

        foreach ($this->splitList($raw, '|') as $index => $path) {
            ProductImage::create([
                'product_id' => $productId,
                'image' => $path,
                'is_featured' => $index === 0,
                'sort_order' => $index,
            ]);
        }
    }

    private function syncColors(int $productId, mixed $colorsCell): void
    {
        $raw = $this->clean($colorsCell ?? '');

        if ($raw === '') {
            return;
        }

        if (mb_strtolower($raw) === 'none') {
            ProductColor::where('product_id', $productId)->delete();
            return;
        }

        ProductColor::where('product_id', $productId)->delete();

        if (mb_strtolower($raw) === 'default') {
            $defaults = [
                ['name' => 'Yellow Gold', 'hex_code' => '#D4AF37'],
                ['name' => 'Champagne Gold', 'hex_code' => '#FAEEC8'],
                ['name' => 'White Gold', 'hex_code' => '#C0C0C0'],
                ['name' => 'Rose Gold', 'hex_code' => '#E0BFB8'],
            ];

            foreach ($defaults as $index => $color) {
                ProductColor::create([
                    'product_id' => $productId,
                    'name' => $color['name'],
                    'hex_code' => $color['hex_code'],
                    'sort_order' => $index,
                ]);
            }

            return;
        }

        foreach ($this->splitList($raw, '|') as $index => $item) {
            [$colorName, $hex] = array_pad(explode(':', $item, 2), 2, '');
            $colorName = $this->clean($colorName);
            $hex = strtoupper($this->clean($hex));

            if ($colorName === '' || !preg_match('/^#[0-9A-F]{6}$/', $hex)) {
                throw new RuntimeException('Invalid Colors format: "' . $item . '". Use Name:#HEX.');
            }

            ProductColor::create([
                'product_id' => $productId,
                'name' => $colorName,
                'hex_code' => $hex,
                'sort_order' => $index,
            ]);
        }
    }

    private function syncSizes(int $productId, mixed $sizesCell): void
    {
        $raw = $this->clean($sizesCell ?? '');

        if ($raw === '') {
            return;
        }

        if (mb_strtolower($raw) === 'none') {
            ProductSize::where('product_id', $productId)->delete();
            return;
        }

        ProductSize::where('product_id', $productId)->delete();

        if (mb_strtolower($raw) === 'default') {
            $defaults = [
                'Ring 6 (d: 14.1mm, c: 44.3mm)', 'Ring 7 (d: 14.5mm, c: 45.5mm)',
                'Ring 8 (d: 14.8mm, c: 46.5mm)', 'Ring 9 (d: 15.2mm, c: 47.7mm)',
                'Ring 10 (d: 15.5mm, c: 48.7mm)', 'Ring 11 (d: 15.9mm, c: 49.9mm)',
                'Ring 12 (d: 16.2mm, c: 50.9mm)', 'Ring 13 (d: 16.6mm, c: 52.1mm)',
                'Ring 14 (d: 16.9mm, c: 53.1mm)', 'Ring 15 (d: 17.3mm, c: 54.3mm)',
                'Ring 16 (d: 17.7mm, c: 55.6mm)', 'Ring 17 (d: 18.0mm, c: 56.5mm)',
                'Ring 18 (d: 18.3mm, c: 57.5mm)', 'Ring 19 (d: 18.7mm, c: 58.7mm)',
                'Ring 20 (d: 19.0mm, c: 59.7mm)', 'Ring 21 (d: 19.4mm, c: 60.9mm)',
                'Ring 22 (d: 19.7mm, c: 61.9mm)', 'Ring 23 (d: 20.1mm, c: 63.1mm)',
                'Ring 24 (d: 20.4mm, c: 64.1mm)', 'Ring 25 (d: 20.8mm, c: 65.3mm)',
                'Others',
            ];

            foreach ($defaults as $index => $sizeName) {
                ProductSize::create([
                    'product_id' => $productId,
                    'size_name' => $sizeName,
                    'sort_order' => $index,
                ]);
            }

            return;
        }

        foreach ($this->splitList($raw, '|') as $index => $sizeName) {
            ProductSize::create([
                'product_id' => $productId,
                'size_name' => $sizeName,
                'sort_order' => $index,
            ]);
        }
    }

    private function syncSpecifications(int $productId, mixed $specificationsCell): void
    {
        $raw = $this->clean($specificationsCell ?? '');

        if ($raw === '') {
            return;
        }

        if (mb_strtolower($raw) === 'none') {
            ProductSpecification::where('product_id', $productId)->delete();
            return;
        }

        ProductSpecification::where('product_id', $productId)->delete();

        $sort = 0;

        foreach (preg_split('/\s*;\s*/', $raw) ?: [] as $section) {
            $section = $this->clean($section);

            if ($section === '') {
                continue;
            }

            [$title, $itemsRaw] = array_pad(explode(':', $section, 2), 2, '');
            $title = $this->clean($title);
            $itemsRaw = $this->clean($itemsRaw);

            if ($title === '' || $itemsRaw === '') {
                throw new RuntimeException('Invalid Specifications format: "' . $section . '".');
            }

            $items = $this->splitList($itemsRaw, '|');

            if (empty($items)) {
                throw new RuntimeException('Specifications items are empty for section: "' . $title . '"');
            }

            ProductSpecification::create([
                'product_id' => $productId,
                'section_title' => $title,
                'items' => $items,
                'sort_order' => $sort++,
            ]);
        }
    }

    private function splitList(string $raw, string $separator): array
    {
        return array_values(array_filter(
            array_map(fn ($value) => $this->clean($value), explode($separator, $raw)),
            fn ($value) => $value !== ''
        ));
    }

    private function parseMoney(mixed $value): float
    {
        $clean = trim(preg_replace('/[^\d,\.\-]/', '', $this->clean($value ?? '')) ?? '');

        if ($clean === '' || $clean === '-') {
            return 0.0;
        }

        if (str_contains($clean, '.') && str_contains($clean, ',')) {
            $clean = str_replace(['.', ','], ['', '.'], $clean);
        } elseif (str_contains($clean, ',')) {
            $clean = str_replace(',', '.', $clean);
        }

        return (float) $clean;
    }

    private function nullIfEmpty(mixed $value): ?string
    {
        $clean = $this->clean($value ?? '');

        return $clean === '' ? null : $clean;
    }

    private function clean(mixed $value): string
    {
        $clean = trim((string) $value);

        return preg_replace('/\s+/', ' ', $clean) ?? $clean;
    }

    private function makeUniqueSlug(string $value): string
    {
        $base = Str::slug($value);

        if ($base === '') {
            $base = Str::lower(Str::random(12));
        }

        $slug = $base;
        $counter = 2;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}