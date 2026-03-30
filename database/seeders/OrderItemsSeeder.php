<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemsSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [];
        $rowId = 1;
        $itemsPerOrder = 3;
        $totalItems = 60;

        for ($order = 1; $order <= 240; $order++) {
            $baseItem = (($order - 1) * $itemsPerOrder) % $totalItems;

            for ($j = 0; $j < $itemsPerOrder; $j++) {
                $itemNumber = (($baseItem + $j) % $totalItems) + 1;

                $rows[] = [
                    'id' => sprintf('90000000-0000-0000-0000-%012d', $rowId),
                    'order_id' => sprintf('80000000-0000-0000-0000-%012d', $order),
                    'item_id' => sprintf('70000000-0000-0000-0000-%012d', $itemNumber),
                    'quantity' => (($order + $j) % 10) + 1,
                ];

                $rowId++;
            }
        }

        DB::table('order_items')->insert($rows);
    }
}
