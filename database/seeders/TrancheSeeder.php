<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrancheSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('tranche')->upsert(
            [
                ['number' => 1, 'name' => '1st Tranche', 'created_at' => $now, 'updated_at' => $now],
                ['number' => 2, 'name' => '2nd Tranche', 'created_at' => $now, 'updated_at' => $now],
            ],
            ['number'],
            ['name', 'updated_at'],
        );
    }
}