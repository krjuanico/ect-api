<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PsgcSeeder extends Seeder
{
    public function run(): void
    {
        // Order matters: each table references the one before it.
        $this->call([
            RegionSeeder::class,
            ProvinceSeeder::class,
            MunicipalitySeeder::class,
            BarangaySeeder::class,
        ]);
    }
}
