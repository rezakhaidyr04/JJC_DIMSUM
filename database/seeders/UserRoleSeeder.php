<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::where('email', 'owner@jjc-dimsum.test')->delete();

        User::updateOrCreate(
            ['email' => 'issmadamayanti@gmail.com'],
            [
                'name' => 'Owner',
                'role' => 'owner',
                'password' => Hash::make('password123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'karyawan@jjc-dimsum.test'],
            [
                'name' => 'Karyawan',
                'role' => 'karyawan',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
