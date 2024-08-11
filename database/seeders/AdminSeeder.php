<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@admin'],
            [
                'name' => 'Admin',
                'email' => 'admin@admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
    }
}
