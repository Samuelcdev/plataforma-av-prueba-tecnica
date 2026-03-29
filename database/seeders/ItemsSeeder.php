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
                'name' => 'Pantalla LED P2.9 3x2m',
                'description' => 'Pantalla LED modular de alta definicion ideal para presentaciones en salones principales.',
                'price' => 1200000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000002',
                'name' => 'Microfono Inalambrico Shure',
                'description' => 'Set de sistema inalambrico de mano o solapa para oradores.',
                'price' => 150000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000003',
                'name' => 'Camara de Video 4K PTZ',
                'description' => 'Camara robotizada para grabacion y transmision en vivo del evento.',
                'price' => 350000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000004',
                'name' => 'Proyector Laser 10,000 Lumnenes',
                'description' => 'Proyector de alta luminosidad para grandes formatos.',
                'price' => 600000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000005',
                'name' => 'Consola de Audio Digital',
                'description' => 'Mesa de mezcla digital de 32 canales para eventos complejos.',
                'price' => 400000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000006',
                'name' => 'Sistema de Sonido Line Array',
                'description' => 'Sonido profesional para hasta 500 asistentes por modulo.',
                'price' => 850000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000007',
                'name' => 'Luces Perimetrales (Par LED)',
                'description' => 'Iluminacion arquitectonica para ambientar el salon.',
                'price' => 80000.00,
                'is_active' => true,
                'created_at' => '2026-03-28 00:00:00',
            ],
            [
                'id' => '70000000-0000-0000-0000-000000000008',
                'name' => 'Operador Audiovisual',
                'description' => 'Asistencia tecnica permanente durante todo el evento.',
                'price' => 250000.00,
                'is_active' => false,
                'created_at' => '2026-03-28 00:00:00',
            ],
        ]);
    }
}
