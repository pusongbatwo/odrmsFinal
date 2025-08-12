<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'full_name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => now(),
            ],
            [
                'full_name' => 'Registrar User',
                'username' => 'registrar',
                'email' => 'registrar@example.com',
                'password' => Hash::make('password123'),
                'role' => 'registrar',
                'created_at' => now(),
            ],
            [
                'full_name' => 'Cashier User',
                'username' => 'cashier',
                'email' => 'cashier@example.com',
                'password' => Hash::make('password123'),
                'role' => 'cashier',
                'created_at' => now(),
            ],
        ]);
    }
}