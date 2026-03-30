<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsSeeder extends Seeder
{
    public function run(): void
    {
        $createdAt = '2026-03-28 00:00:00';

        $catalog = [
            ['Pantalla LED P2.9 3x2m', 'Pantalla LED modular de alta definicion para salones principales.', 1200000],
            ['Pantalla LED P3.9 4x2m', 'Pantalla LED para convenciones y lanzamientos de marca.', 1350000],
            ['Microfono Inalambrico Shure', 'Set inalambrico de mano o solapa para conferencistas.', 150000],
            ['Microfono Cuello de Ganso', 'Microfono para mesas principales y paneles.', 45000],
            ['Camara PTZ 4K', 'Camara robotizada para streaming y grabacion multicamara.', 350000],
            ['Switcher de Video 8 Entradas', 'Consola para mezcla de senales HDMI/SDI.', 480000],
            ['Proyector Laser 10000 Lumens', 'Proyeccion de alta luminosidad para escenarios grandes.', 600000],
            ['Consola de Audio Digital 32ch', 'Mezcla profesional para eventos corporativos.', 400000],
            ['Sistema Line Array', 'Cobertura de sonido profesional por modulo.', 850000],
            ['Subwoofer Activo 18"', 'Refuerzo de graves para conciertos y fiestas.', 180000],
            ['Par LED RGBW', 'Iluminacion ambiental y de escenario.', 80000],
            ['Cabeza Movil Beam 230', 'Iluminacion dinamica para shows y tarima.', 135000],
            ['Backline Basico de Tarima', 'Monitores, pies de microfono y cableado base.', 220000],
            ['Tarima Modular 6x4m', 'Plataforma de escenario en modulos.', 550000],
            ['Podio Corporativo', 'Podio para voceria institucional.', 90000],
            ['Computador de Presentacion', 'Equipo dedicado para presentaciones y videos.', 120000],
            ['Clicker Profesional', 'Control remoto para diapositivas.', 25000],
            ['UPS para Rack AV', 'Respaldo electrico para equipos criticos.', 110000],
            ['Router WiFi Empresarial', 'Red dedicada para streaming y asistentes.', 150000],
            ['Codificador Streaming RTMP', 'Codificacion de video para plataformas en vivo.', 260000],
            ['Grabacion Full HD', 'Servicio de grabacion de jornada completa.', 320000],
            ['Fotografia de Evento', 'Cobertura fotografica corporativa por jornada.', 300000],
            ['Video Resumen 2 Min', 'Edicion de video resumen del evento.', 450000],
            ['Planta Electrica 20kVA', 'Respaldo energetico para eventos externos.', 500000],
            ['Extensiones y Distribucion', 'Cables y distribucion electrica certificada.', 85000],
            ['Intercom de Produccion', 'Comunicacion interna para equipo tecnico.', 90000],
            ['Truss de Iluminacion', 'Estructura para montaje de luces y pendones.', 280000],
            ['Impresion de Escarapelas', 'Escarapelas personalizadas para asistentes.', 180000],
            ['Registro de Asistentes', 'Control de ingreso y acreditacion.', 220000],
            ['Host de Protocolo', 'Apoyo de protocolo y direccion de escenario.', 260000],
        ];

        $rows = [];
        $id = 1;

        for ($i = 0; $i < 2; $i++) {
            foreach ($catalog as $item) {
                $rows[] = [
                    'id' => sprintf('70000000-0000-0000-0000-%012d', $id),
                    'name' => $i === 0 ? $item[0] : $item[0] . ' - Kit ' . ($i + 1),
                    'description' => $item[1],
                    'price' => $item[2] + ($i * 15000),
                    'is_active' => ($id % 13) !== 0,
                    'created_at' => $createdAt,
                ];
                $id++;
            }
        }

        DB::table('items')->insert($rows);
    }
}
