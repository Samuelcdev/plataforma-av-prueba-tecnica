<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'id' => '80000000-0000-0000-0000-000000000001',
                'hotel_id' => '60000000-0000-0000-0000-000000000001',
                'name' => 'Orden Semanal Andes 01',
                'service_type' => 'Limpieza',
                'start_date' => '2026-04-01 08:00:00',
                'end_date' => '2026-04-01 18:00:00',
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '80000000-0000-0000-0000-000000000002',
                'hotel_id' => '60000000-0000-0000-0000-000000000001',
                'name' => 'Orden Semanal Andes 02',
                'service_type' => 'Mantenimiento',
                'start_date' => '2026-04-03 09:00:00',
                'end_date' => '2026-04-03 16:30:00',
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '80000000-0000-0000-0000-000000000003',
                'hotel_id' => '60000000-0000-0000-0000-000000000002',
                'name' => 'Orden Pacifico 01',
                'service_type' => 'Lavanderia',
                'start_date' => '2026-04-02 07:30:00',
                'end_date' => '2026-04-02 14:00:00',
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '80000000-0000-0000-0000-000000000004',
                'hotel_id' => '60000000-0000-0000-0000-000000000002',
                'name' => 'Orden Pacifico 02',
                'service_type' => 'Desinfeccion',
                'start_date' => '2026-04-05 10:00:00',
                'end_date' => '2026-04-05 20:00:00',
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '80000000-0000-0000-0000-000000000005',
                'hotel_id' => '60000000-0000-0000-0000-000000000003',
                'name' => 'Orden Oriente 01',
                'service_type' => 'Aseo General',
                'start_date' => '2026-04-04 06:00:00',
                'end_date' => '2026-04-04 13:00:00',
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '80000000-0000-0000-0000-000000000006',
                'hotel_id' => '60000000-0000-0000-0000-000000000003',
                'name' => 'Orden Oriente 02',
                'service_type' => 'Limpieza',
                'start_date' => '2026-04-06 08:15:00',
                'end_date' => '2026-04-06 17:00:00',
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
            ],
        ]);
    }
}
