<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // unique check by email
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'), // change this later
                'role' => 'admin',
            ]
        );
    }
}
