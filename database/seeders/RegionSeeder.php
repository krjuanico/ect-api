<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        // Region.csv columns: 10-digit PSGC, Name
        $path = database_path('data/Region.csv');
        $file = fopen($path, 'r');
        fgetcsv($file);

        $rows = [];
        $now = now();

        while (($row = fgetcsv($file)) !== false) {
            if (trim($row[0] ?? '') === '') {
                continue;
            }

            $name = trim($row[1]);

            // A CSV saved as plain "CSV" from Excel is Windows-1252, not UTF-8.
            if (! mb_check_encoding($name, 'UTF-8')) {
                $name = mb_convert_encoding($name, 'UTF-8', 'Windows-1252');
            }

            $rows[] = [
                'psgc_code' => str_pad(trim($row[0]), 10, '0', STR_PAD_LEFT),
                'name' => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        fclose($file);

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('regions')->insertOrIgnore($chunk);
        }
    }
}
