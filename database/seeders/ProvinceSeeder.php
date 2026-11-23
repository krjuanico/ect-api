<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        // Province.csv columns: 10-digit PSGC, Name
        // region_code is derived from the first two digits of the PSGC code.
        $path = database_path('data/Province.csv');
        $file = fopen($path, 'r');
        fgetcsv($file);

        $regionCodes = DB::table('regions')->pluck('psgc_code')->flip()->all();

        $rows = [];
        $skipped = 0;
        $now = now();

        while (($row = fgetcsv($file)) !== false) {
            if (trim($row[0] ?? '') === '') {
                continue;
            }

            $code = str_pad(trim($row[0]), 10, '0', STR_PAD_LEFT);
            $regionCode = substr($code, 0, 2) . '00000000';

            if (! isset($regionCodes[$regionCode])) {
                $skipped++;
                continue;
            }

            $name = trim($row[1]);

            if (! mb_check_encoding($name, 'UTF-8')) {
                $name = mb_convert_encoding($name, 'UTF-8', 'Windows-1252');
            }

            $rows[] = [
                'psgc_code' => $code,
                'region_code' => $regionCode,
                'name' => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        fclose($file);

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('provinces')->insertOrIgnore($chunk);
        }

        if ($skipped > 0) {
            $this->command?->warn("ProvinceSeeder: skipped {$skipped} rows with no matching region.");
        }
    }
}
