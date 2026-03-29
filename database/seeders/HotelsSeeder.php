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
                'id' => '019d3820-3fcd-721e-97b9-152da9a7d53c',
                'user_id' => '019d379e-0915-74a8-b55a-f133ddd7daee',
                'nit' => '900111001-1',
                'document_type' => 'NIT',
                'name' => 'Hotel Andes',
                'phone' => '+5716010001',
                'address' => 'Cra 10 # 20-30, Bogota',
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '019d3820-3fcd-721e-97b9-1bb0fbf54534',
                'user_id' => '019d379e-0915-74a8-b55a-f76438c00c81',
                'nit' => '900111002-2',
                'document_type' => 'NIT',
                'name' => 'Hotel Pacifico',
                'phone' => '+5726010002',
                'address' => 'Av del Mar # 8-15, Cali',
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '019d3820-3fcd-721e-97b9-1e1995e6afbd',
                'user_id' => '019d379e-0915-74a8-b55a-f9d7f917b5a4',
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
