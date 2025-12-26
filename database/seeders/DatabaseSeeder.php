<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default Admin
        User::factory()->create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'Admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Default Pimpinan
        User::factory()->create([
            'nama' => 'Pimpinan',
            'username' => 'pimpinan',
            'password' => Hash::make('pimpinan123'),
            'role' => 'Pimpinan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
