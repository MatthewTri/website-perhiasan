<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\ProductSpecification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    private array $errors = [];

    public function run(): void
    {
        $this->call(CategorySeeder::class);

        $file = $this->findDataFile();
        $rows = $this->readFile($file);

        $total   = count($rows);
        $success = 0;
        $skipped = 0;

        $this->command->info("Memproses {$total} baris dari: " . basename($file));

        foreach ($rows as $index => $row) {
            $lineNumber = $index + 2; // +2 karena baris 1 = header
            $name = $this->clean($row['Nama'] ?? '');

            // Skip baris kosong
            if ($name === '') {
                $skipped++;
                continue;
            }

            // Skip duplikat — sudah ada di DB, tidak perlu diulang
            if (Product::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->exists()) {
                $this->command->line("  [SKIP] Baris {$lineNumber}: \"{$name}\" sudah ada di database");
                $skipped++;
                continue;
            }

            // Proses row — kalau error, skip baris ini saja, tidak crash semua
            try {
                $this->processRow($row, $name);
                $success++;
                $this->command->line("  [OK]   Baris {$lineNumber}: \"{$name}\"");
            } catch (\Throwable $e) {
                $skipped++;
                $message = "Baris {$lineNumber} (\"{$name}\"): {$e->getMessage()}";
                $this->errors[] = $message;
                $this->command->warn("  [ERR]  {$message}");
                Log::warning("ProductSeeder — {$message}");
            }
        }

        // Summary
        $this->command->newLine();
        $this->command->info("Selesai. Berhasil: {$success} | Dilewati/Error: {$skipped} | Total: {$total}");

        if (!empty($this->errors)) {
            $this->command->newLine();
            $this->command->warn('Baris berikut perlu diperbaiki di file data:');
            foreach ($this->errors as $err) {
                $this->command->warn("  - {$err}");
            }
            $this->command->newLine();
            $this->command->warn('Setelah diperbaiki, jalankan: php artisan db:seed --class=ProductSeeder');
            $this->command->warn('Row yang sudah masuk tidak akan dobel — hanya yang diperbaiki saja yang ditambah.');
        }
    }

    // ── Proses satu row ──────────────────────────────────────────────────────

    private function processRow(array $row, string $name): void
    {
        DB::transaction(function () use ($row, $name) {
            $categoryId = $this->resolveCategory(
                $this->clean($row['Parent-Kategori'] ?? ''),
                $this->clean($row['Kategori'] ?? ''),
            );

            $product = Product::create([
                'name'         => $name,
                'description'  => $this->nullIfEmpty($row['Deskripsi'] ?? null),
                'price_idr'    => $this->parseMoney($row['Harga_IDR'] ?? 0),
                'price_usd'    => $this->parseMoney($row['Harga_USD'] ?? 0),
                'category_id'  => $categoryId,
                'rating_avg'   => 0,
                'rating_count' => 0,
            ]);

            $this->seedImages($product->id, $row['Photos'] ?? null);
            $this->seedColors($product->id, $row['Colors'] ?? null);
            $this->seedSizes($product->id, $row['Sizes'] ?? null);
            $this->seedSpecifications($product->id, $row['Specifications'] ?? null);
        });
    }

    private function resolveCategory(string $parentName, string $childName): int
    {
        if ($parentName === '') {
            throw new \RuntimeException("Kolom Parent-Kategori kosong");
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

    // ── Seed relasi ──────────────────────────────────────────────────────────

    private function seedImages(int $productId, mixed $photosCell): void
    {
        $raw = $this->clean($photosCell ?? '');
        if ($raw === '' || mb_strtolower($raw) === 'none') return;

        foreach ($this->splitList($raw, '|') as $i => $path) {
            ProductImage::create([
                'product_id'  => $productId,
                'image'       => $path,
                'is_featured' => $i === 0,
                'sort_order'  => $i,
            ]);
        }
    }

    private function seedColors(int $productId, mixed $colorsCell): void
    {
        $raw = $this->clean($colorsCell ?? '');
        if ($raw === '' || mb_strtolower($raw) === 'none') return;

        if (mb_strtolower($raw) === 'default') {
            $defaults = [
                ['name' => 'Yellow Gold',    'hex_code' => '#D4AF37'],
                ['name' => 'Champagne Gold', 'hex_code' => '#FAEEC8'],
                ['name' => 'White Gold',     'hex_code' => '#C0C0C0'],
                ['name' => 'Rose Gold',      'hex_code' => '#E0BFB8'],
            ];
            foreach ($defaults as $i => $c) {
                ProductColor::create([
                    'product_id' => $productId,
                    'name'       => $c['name'],
                    'hex_code'   => $c['hex_code'],
                    'sort_order' => $i,
                ]);
            }
            return;
        }

        foreach ($this->splitList($raw, '|') as $i => $item) {
            [$colorName, $hex] = array_pad(explode(':', $item, 2), 2, '');
            $colorName = $this->clean($colorName);
            $hex       = strtoupper($this->clean($hex));

            if ($colorName === '' || !preg_match('/^#[0-9A-F]{6}$/', $hex)) {
                throw new \RuntimeException(
                    "Format Colors tidak valid: \"{$item}\" — contoh benar: Yellow Gold:#D4AF37"
                );
            }

            ProductColor::create([
                'product_id' => $productId,
                'name'       => $colorName,
                'hex_code'   => $hex,
                'sort_order' => $i,
            ]);
        }
    }

    private function seedSizes(int $productId, mixed $sizesCell): void
    {
        $raw = $this->clean($sizesCell ?? '');
        if ($raw === '' || mb_strtolower($raw) === 'none') return;

        if (mb_strtolower($raw) === 'default') {
            $defaults = [
                "Ring 6 (d: 14.1mm, c: 44.3mm)",  "Ring 7 (d: 14.5mm, c: 45.5mm)",
                "Ring 8 (d: 14.8mm, c: 46.5mm)",  "Ring 9 (d: 15.2mm, c: 47.7mm)",
                "Ring 10 (d: 15.5mm, c: 48.7mm)", "Ring 11 (d: 15.9mm, c: 49.9mm)",
                "Ring 12 (d: 16.2mm, c: 50.9mm)", "Ring 13 (d: 16.6mm, c: 52.1mm)",
                "Ring 14 (d: 16.9mm, c: 53.1mm)", "Ring 15 (d: 17.3mm, c: 54.3mm)",
                "Ring 16 (d: 17.7mm, c: 55.6mm)", "Ring 17 (d: 18.0mm, c: 56.5mm)",
                "Ring 18 (d: 18.3mm, c: 57.5mm)", "Ring 19 (d: 18.7mm, c: 58.7mm)",
                "Ring 20 (d: 19.0mm, c: 59.7mm)", "Ring 21 (d: 19.4mm, c: 60.9mm)",
                "Ring 22 (d: 19.7mm, c: 61.9mm)", "Ring 23 (d: 20.1mm, c: 63.1mm)",
                "Ring 24 (d: 20.4mm, c: 64.1mm)", "Ring 25 (d: 20.8mm, c: 65.3mm)",
                "Others",
            ];
            foreach ($defaults as $i => $sizeName) {
                ProductSize::create([
                    'product_id' => $productId,
                    'size_name'  => $sizeName,
                    'sort_order' => $i,
                ]);
            }
            return;
        }

        foreach ($this->splitList($raw, '|') as $i => $sizeName) {
            ProductSize::create([
                'product_id' => $productId,
                'size_name'  => $sizeName,
                'sort_order' => $i,
            ]);
        }
    }

    private function seedSpecifications(int $productId, mixed $specCell): void
    {
        $raw = $this->clean($specCell ?? '');
        if ($raw === '' || mb_strtolower($raw) === 'none') return;

        $sort = 0;
        foreach (preg_split('/\s*;\s*/', $raw) ?: [] as $section) {
            $section = $this->clean($section);
            if ($section === '') continue;

            [$title, $itemsRaw] = array_pad(explode(':', $section, 2), 2, '');
            $title    = $this->clean($title);
            $itemsRaw = $this->clean($itemsRaw);

            if ($title === '' || $itemsRaw === '') {
                throw new \RuntimeException(
                    "Format Specifications tidak valid: \"{$section}\" — contoh benar: Material: 18K Gold|Nickel-Free"
                );
            }

            $items = $this->splitList($itemsRaw, '|');
            if (empty($items)) {
                throw new \RuntimeException("Specifications items kosong pada section: \"{$title}\"");
            }

            ProductSpecification::create([
                'product_id'    => $productId,
                'section_title' => $title,
                'items'         => $items,
                'sort_order'    => $sort++,
            ]);
        }
    }

    // ── Baca file ────────────────────────────────────────────────────────────

    private function findDataFile(): string
    {
        $base = database_path('seeders/data/products');

        foreach (['tsv', 'csv'] as $ext) {
            if (is_file("{$base}.{$ext}")) {
                return "{$base}.{$ext}";
            }
        }

        throw new \RuntimeException(
            "File data tidak ditemukan. Letakkan salah satu:\n" .
            "  database/seeders/data/products.tsv\n" .
            "  database/seeders/data/products.csv"
        );
    }

    private function readFile(string $path): array
    {
        $delimiter = str_ends_with($path, '.tsv') ? "\t" : ',';

        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Tidak bisa membuka file: {$path}");
        }

        $header = fgetcsv($handle, 0, $delimiter);
        if ($header === false) {
            fclose($handle);
            return [];
        }

        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header[0]);
        $header    = array_map(fn($h) => trim((string) $h), $header);

        $rows = [];
        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (count($data) === 1 && trim((string) $data[0]) === '') continue;

            $assoc = [];
            foreach ($header as $i => $key) {
                $assoc[$key] = $data[$i] ?? null;
            }
            $rows[] = $assoc;
        }

        fclose($handle);
        return $rows;
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function splitList(string $raw, string $sep): array
    {
        return array_values(array_filter(
            array_map(fn($v) => $this->clean($v), explode($sep, $raw)),
            fn($v) => $v !== ''
        ));
    }

    private function parseMoney(mixed $value): float
    {
        $s = trim(preg_replace('/[^\d,.\-]/', '', $this->clean($value ?? '')) ?? '');

        if ($s === '' || $s === '-') return 0.0;

        if (str_contains($s, '.') && str_contains($s, ',')) {
            $s = str_replace(['.', ','], ['', '.'], $s);
        } elseif (str_contains($s, ',')) {
            $s = str_replace(',', '.', $s);
        }

        return (float) $s;
    }

    private function nullIfEmpty(mixed $value): ?string
    {
        $s = $this->clean($value ?? '');
        return $s === '' ? null : $s;
    }

    private function clean(mixed $value): string
    {
        $s = trim((string) $value);
        return preg_replace('/\s+/', ' ', $s) ?? $s;
    }
}