<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin', 'created_at' => '2026-03-28 00:00:00'],
            ['id' => 2, 'name' => 'hotel', 'created_at' => '2026-03-28 00:00:00'],
            ['id' => 3, 'name' => 'operative', 'created_at' => '2026-03-28 00:00:00'],
        ]);
    }
}
