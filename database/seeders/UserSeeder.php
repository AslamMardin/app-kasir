<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Utama',
            'username' => 'admin',
            'email' => 'admin@campalagian.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Supervisor Toko',
            'username' => 'spv',
            'password' => Hash::make('spv123'),
            'role' => 'supervisor',
        ]);

        User::create([
            'name' => 'Kasir 1',
            'username' => 'kasir1',
            'password' => Hash::make('kasir123'),
            'role' => 'kasir',
        ]);
    }
}
