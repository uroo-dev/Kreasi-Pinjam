<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder Data Admin
        User::factory()->create([
            'nama' => 'uroo',
            'email' => 'uroo@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'no_hp' => '0882006533693',
        ]);
    }
}
