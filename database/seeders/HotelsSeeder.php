<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HotelsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hotels')->insert([
            [
                'id' => '60000000-0000-0000-0000-000000000001',
                'user_id' => '20000000-0000-0000-0000-000000000001',
                'nit' => '900111001-1',
                'document_type' => 'NIT',
                'name' => 'Hotel Andes',
                'phone' => '+5716010001',
                'address' => 'Cra 10 # 20-30, Bogota',
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '60000000-0000-0000-0000-000000000002',
                'user_id' => '20000000-0000-0000-0000-000000000002',
                'nit' => '900111002-2',
                'document_type' => 'NIT',
                'name' => 'Hotel Pacifico',
                'phone' => '+5726010002',
                'address' => 'Av del Mar # 8-15, Cali',
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '60000000-0000-0000-0000-000000000003',
                'user_id' => '20000000-0000-0000-0000-000000000003',
                'nit' => '900111003-3',
                'document_type' => 'RUT',
                'name' => 'Hotel Oriente',
                'phone' => '+5746010003',
                'address' => 'Cll 45 # 12-98, Medellin',
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
            ],
        ]);
    }
}
