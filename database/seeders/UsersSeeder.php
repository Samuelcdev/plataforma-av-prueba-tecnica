<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => '019d379e-0915-74a8-b55a-eea8dd6536f0',
                'username' => 'admin.super',
                'password' => Hash::make('Admin123!'),
                'role_id' => 1,
                'is_active' => true,
                'last_login_at' => null,
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
                'deleted_at' => null,
            ],
            [
                'id' => '019d379e-0915-74a8-b55a-e8b0f0e1cbeb',
                'username' => 'admin.ops',
                'password' => Hash::make('Admin123!'),
                'role_id' => 1,
                'is_active' => true,
                'last_login_at' => null,
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
                'deleted_at' => null,
            ],
            [
                'id' => '019d379e-0915-74a8-b55a-f133ddd7daee',
                'username' => 'hotel.andes',
                'password' => Hash::make('Hotel123!'),
                'role_id' => 2,
                'is_active' => true,
                'last_login_at' => null,
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
                'deleted_at' => null,
            ],
            [
                'id' => '019d379e-0915-74a8-b55a-f76438c00c81',
                'username' => 'hotel.pacifico',
                'password' => Hash::make('Hotel123!'),
                'role_id' => 2,
                'is_active' => true,
                'last_login_at' => null,
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
                'deleted_at' => null,
            ],
            [
                'id' => '019d379e-0915-74a8-b55a-f9d7f917b5a4',
                'username' => 'hotel.oriente',
                'password' => Hash::make('Hotel123!'),
                'role_id' => 2,
                'is_active' => true,
                'last_login_at' => null,
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
                'deleted_at' => null,
            ],
            [
                'id' => '019d379e-0915-74a8-b55a-fcc8312bf69e',
                'username' => 'op.norte',
                'password' => Hash::make('Operative123!'),
                'role_id' => 3,
                'is_active' => true,
                'last_login_at' => null,
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
                'deleted_at' => null,
            ],
            [
                'id' => '019d379e-0915-74a8-b55b-01d5957232e9',
                'username' => 'op.sur',
                'password' => Hash::make('Operative123!'),
                'role_id' => 3,
                'is_active' => true,
                'last_login_at' => null,
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
                'deleted_at' => null,
            ],
            [
                'id' => '019d379e-0915-74a8-b55b-0540aa78168d',
                'username' => 'op.centro',
                'password' => Hash::make('Operative123!'),
                'role_id' => 3,
                'is_active' => true,
                'last_login_at' => null,
                'created_at' => '2026-03-28 00:00:00',
                'updated_at' => '2026-03-28 00:00:00',
                'deleted_at' => null,
            ],
        ]);
    }
}
