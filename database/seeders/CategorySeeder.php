<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $file = $this->findDataFile();
        $rows = $this->readFile($file);

        foreach ($rows as $row) {
            $parentName = $this->clean($row['Parent-Kategori'] ?? '');
            if ($parentName === '') continue;

            $parent = Category::firstOrCreate(
                ['slug' => Str::slug($parentName)],
                ['name' => $parentName, 'parent_id' => null]
            );

            $childName = $this->clean($row['Kategori'] ?? '');
            if ($childName === '') continue;

            $child = Category::firstOrCreate(
                ['slug' => Str::slug($childName)],
                ['name' => $childName, 'parent_id' => $parent->id]
            );

            if ($child->parent_id !== $parent->id) {
                $child->update(['parent_id' => $parent->id]);
            }
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

    // ── Helper ───────────────────────────────────────────────────────────────

    private function clean(mixed $value): string
    {
        $s = trim((string) $value);
        return preg_replace('/\s+/', ' ', $s) ?? $s;
    }
}