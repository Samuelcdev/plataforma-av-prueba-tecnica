<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('order_assignments')->truncate();
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::table('items')->truncate();
        DB::table('hotels')->truncate();
        DB::table('operatives')->truncate();
        DB::table('admins')->truncate();
        DB::table('users')->truncate();
        DB::table('roles')->truncate();

        Schema::enableForeignKeyConstraints();

        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            AdminsSeeder::class,
            OperativesSeeder::class,
            HotelsSeeder::class,
            ItemsSeeder::class,
            OrdersSeeder::class,
            OrderItemsSeeder::class,
            OrderAssignmentsSeeder::class,
        ]);
    }
}
