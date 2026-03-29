<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperativesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('operatives')->insert([
            [
                'id' => '50000000-0000-0000-0000-000000000001',
                'document_type' => 'CC',
                'document' => '7007007001',
                'name' => 'Operativo Norte',
                'is_active' => true,
            ],
            [
                'id' => '50000000-0000-0000-0000-000000000002',
                'document_type' => 'CC',
                'document' => '7007007002',
                'name' => 'Operativo Sur',
                'is_active' => true,
            ],
            [
                'id' => '50000000-0000-0000-0000-000000000003',
                'document_type' => 'TI',
                'document' => '7007007003',
                'name' => 'Operativo Centro',
                'is_active' => true,
            ],
            [
                'id' => '50000000-0000-0000-0000-000000000004',
                'document_type' => 'CE',
                'document' => '7007007004',
                'name' => 'Operativo Occidente',
                'is_active' => false,
            ],
        ]);
    }
}
