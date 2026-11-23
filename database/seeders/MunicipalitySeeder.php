<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipalitySeeder extends Seeder
{
    public function run(): void
    {
        // Municipality.csv columns: 10-digit PSGC, Name
        // region_code comes from the first two digits of the PSGC code.
        // province_code comes from the first five digits, and is NULL when no such province exists (e.g. NCR).
        // type is left NULL because the CSV doesn't carry it.
        $path = database_path('data/Municipality.csv');
        $file = fopen($path, 'r');
        fgetcsv($file);

        $regionCodes = DB::table('regions')->pluck('psgc_code')->flip()->all();
        $provinceCodes = DB::table('provinces')->pluck('psgc_code')->flip()->all();

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

            $provinceCode = substr($code, 0, 5) . '00000';

            $name = trim($row[1]);

            if (! mb_check_encoding($name, 'UTF-8')) {
                $name = mb_convert_encoding($name, 'UTF-8', 'Windows-1252');
            }

            $rows[] = [
                'psgc_code' => $code,
                'region_code' => $regionCode,
                'province_code' => isset($provinceCodes[$provinceCode]) ? $provinceCode : null,
                'name' => $name,
                'type' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        fclose($file);

        foreach (array_chunk($rows, 1000) as $chunk) {
            DB::table('municipalities')->insertOrIgnore($chunk);
        }

        if ($skipped > 0) {
            $this->command?->warn("MunicipalitySeeder: skipped {$skipped} rows with no matching region.");
        }
    }
}
