<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Admin A',
                'email' => 'admin1@gmail.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Admin B',
                'email' => 'admin2@siperbbm.local',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Admin C',
                'email' => 'admin3@siperbbm.local',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ],
        ]);
    }
}
