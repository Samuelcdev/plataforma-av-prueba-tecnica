<?php

namespace Database\Seeders;

use DateTimeImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderAssignmentsSeeder extends Seeder
{
    public function run(): void
    {
        $adminIds = [
            '019d3820-0f3d-731f-8ad0-767b93840a22',
            '019d3820-0f3c-70ae-b8d7-ac282e09cc3b',
        ];

        $baseAssignedAt = new DateTimeImmutable('2026-03-28 08:00:00');
        $rows = [];
        $rowId = 1;

        for ($order = 1; $order <= 240; $order++) {
            $operativeOne = (($order - 1) % 35) + 1;
            $operativeTwo = (($order + 10) % 35) + 1;
            if ($operativeTwo === $operativeOne) {
                $operativeTwo = (($operativeTwo + 1) % 35) + 1;
            }

            $firstAssignedAt = $baseAssignedAt->modify(sprintf('+%d minutes', $order * 3));
            $secondAssignedAt = $firstAssignedAt->modify('+6 minutes');

            $rows[] = [
                'id' => sprintf('a0000000-0000-0000-0000-%012d', $rowId++),
                'order_id' => sprintf('80000000-0000-0000-0000-%012d', $order),
                'operative_id' => sprintf('50000000-0000-0000-0000-%012d', $operativeOne),
                'admin_id' => $adminIds[$order % 2],
                'assigned_at' => $firstAssignedAt->format('Y-m-d H:i:s'),
            ];

            $rows[] = [
                'id' => sprintf('a0000000-0000-0000-0000-%012d', $rowId++),
                'order_id' => sprintf('80000000-0000-0000-0000-%012d', $order),
                'operative_id' => sprintf('50000000-0000-0000-0000-%012d', $operativeTwo),
                'admin_id' => $adminIds[($order + 1) % 2],
                'assigned_at' => $secondAssignedAt->format('Y-m-d H:i:s'),
            ];
        }

        DB::table('order_assignments')->insert($rows);
    }
}
