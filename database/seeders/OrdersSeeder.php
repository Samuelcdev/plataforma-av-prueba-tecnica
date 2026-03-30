<?php

namespace Database\Seeders;

use DateTimeImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        $hotelIds = [
            '019d3820-3fcd-721e-97b9-152da9a7d53c',
            '019d3820-3fcd-721e-97b9-1bb0fbf54534',
            '019d3820-3fcd-721e-97b9-1e1995e6afbd',
        ];

        $hotelAlias = [
            'Andes',
            'Pacifico',
            'Oriente',
        ];

        $serviceTypes = [
            'Congreso corporativo',
            'Lanzamiento de producto',
            'Convencion comercial',
            'Seminario academico',
            'Rueda de prensa',
            'Coctel empresarial',
            'Asamblea anual',
            'Capacitacion interna',
        ];

        $baseDate = new DateTimeImmutable('2026-03-30 07:00:00');
        $rows = [];

        for ($i = 1; $i <= 240; $i++) {
            $hotelIndex = ($i - 1) % count($hotelIds);
            $dayOffset = ($i % 180) - 90;
            $start = $baseDate
                ->modify(sprintf('%+d days', $dayOffset))
                ->modify(sprintf('+%d hours', $i % 10));

            $durationHours = 4 + ($i % 8);
            $end = $start->modify(sprintf('+%d hours', $durationHours));

            $status = 'active';
            if ($i % 10 === 0 || $i % 10 === 9) {
                $status = 'pending';
            }
            if ($i % 20 === 0) {
                $status = 'cancelled';
            }

            $createdAt = $start->modify(sprintf('-%d days', 5 + ($i % 14)));
            $updatedAt = $createdAt->modify(sprintf('+%d hours', 1 + ($i % 6)));

            $rows[] = [
                'id' => sprintf('80000000-0000-0000-0000-%012d', $i),
                'hotel_id' => $hotelIds[$hotelIndex],
                'name' => sprintf('%s %s %03d', $serviceTypes[$i % count($serviceTypes)], $hotelAlias[$hotelIndex], $i),
                'service_type' => $serviceTypes[$i % count($serviceTypes)],
                'start_date' => $start->format('Y-m-d H:i:s'),
                'end_date' => $end->format('Y-m-d H:i:s'),
                'status' => $status,
                'created_at' => $createdAt->format('Y-m-d H:i:s'),
                'updated_at' => $updatedAt->format('Y-m-d H:i:s'),
            ];
        }

        DB::table('orders')->insert($rows);
    }
}
