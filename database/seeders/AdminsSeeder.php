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
                'id' => '40000000-0000-0000-0000-000000000001',
                'user_id' => '10000000-0000-0000-0000-000000000001',
                'document_type' => 'CC',
                'document' => '1001001001',
                'name' => 'Ana Admin',
                'email' => 'ana.admin@example.com',
                'phone' => '+573001001001',
            ],
            [
                'id' => '40000000-0000-0000-0000-000000000002',
                'user_id' => '10000000-0000-0000-0000-000000000002',
                'document_type' => 'CE',
                'document' => '2002002002',
                'name' => 'Carlos Operaciones',
                'email' => 'carlos.ops@example.com',
                'phone' => '+573002002002',
            ],
        ]);
    }
}
