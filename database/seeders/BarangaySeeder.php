<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangaySeeder extends Seeder
{
    public function run(): void
    {
        // Barangay.csv columns: 10-digit PSGC, Name
        // municipality_code is derived from the first seven digits of the PSGC code.
        $path = database_path('data/Barangay.csv');
        $file = fopen($path, 'r');
        fgetcsv($file);

        $municipalityCodes = DB::table('municipalities')->pluck('psgc_code')->flip()->all();

        $rows = [];
        $skipped = 0;
        $now = now();

        while (($row = fgetcsv($file)) !== false) {
            if (trim($row[0] ?? '') === '') {
                continue;
            }

            $code = str_pad(trim($row[0]), 10, '0', STR_PAD_LEFT);
            $municipalityCode = substr($code, 0, 7) . '000';

            if (! isset($municipalityCodes[$municipalityCode])) {
                $skipped++;
                continue;
            }

            $name = trim($row[1]);

            if (! mb_check_encoding($name, 'UTF-8')) {
                $name = mb_convert_encoding($name, 'UTF-8', 'Windows-1252');
            }

            $rows[] = [
                'psgc_code' => $code,
                'municipality_code' => $municipalityCode,
                'name' => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        fclose($file);

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('barangays')->insertOrIgnore($chunk);
        }

        if ($skipped > 0) {
            $this->command?->warn("BarangaySeeder: skipped {$skipped} rows with no matching municipality.");
        }
    }
}
