<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('programs')->upsert(
            [
                ['code' => 'ECT', 'name' => 'Emergency Cash Transfer', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
                ['code' => 'CCAM', 'name' => 'Climate Change Adaptation and Mitigation', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ],
            ['code'],
            ['name', 'is_active', 'updated_at'],
        );
    }
}