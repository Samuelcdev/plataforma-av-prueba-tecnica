<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->insert([
            [
                'id' => '019d3820-0f3d-731f-8ad0-767b93840a22',
                'user_id' => '019d379e-0915-74a8-b55a-eea8dd6536f0',
                'document_type' => 'CC',
                'document' => '1001001001',
                'name' => 'Ana Admin',
                'email' => 'ana.admin@example.com',
                'phone' => '+573001001001',
            ],
            [
                'id' => '019d3820-0f3c-70ae-b8d7-ac282e09cc3b',
                'user_id' => '019d379e-0915-74a8-b55a-e8b0f0e1cbeb',
                'document_type' => 'CE',
                'document' => '2002002002',
                'name' => 'Carlos Operaciones',
                'email' => 'carlos.ops@example.com',
                'phone' => '+573002002002',
            ],
        ]);
    }
}
