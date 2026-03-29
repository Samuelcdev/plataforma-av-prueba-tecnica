<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('items')->insert([
            [
                'id' => '70000000-0000-0000-0000-000000000001',
                'name' => 'Limpieza Habitacion Estandar',
                'description' => 'Servicio de limpieza completa para habitacion estandar.',
                'price' => 85000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000002',
                'name' => 'Limpieza Habitacion Suite',
                'description' => 'Servicio de limpieza premium para suite.',
                'price' => 130000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000003',
                'name' => 'Lavanderia Express',
                'description' => 'Lavado y planchado en menos de 12 horas.',
                'price' => 45000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000004',
                'name' => 'Reposicion Amenidades',
                'description' => 'Reposicion de kit de amenidades por habitacion.',
                'price' => 18000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000005',
                'name' => 'Mantenimiento Preventivo AC',
                'description' => 'Chequeo y limpieza de aire acondicionado.',
                'price' => 98000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000006',
                'name' => 'Desinfeccion Profunda',
                'description' => 'Desinfeccion especializada con insumos hospitalarios.',
                'price' => 155000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000007',
                'name' => 'Aseo Zonas Comunes',
                'description' => 'Servicio de aseo de lobby y zonas comunes.',
                'price' => 210000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000008',
                'name' => 'Pulido de Pisos',
                'description' => 'Pulido y brillo para pisos de alto trafico.',
                'price' => 175000.00,
                'is_active' => false,
                'created_at' => '2026-03-28 00:00:00',
            ],
        ]);
    }
}
