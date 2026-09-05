<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')],
        );

        User::firstOrCreate(
            ['email' => 'test2@example.com'],
            ['name' => 'Test User 2', 'password' => bcrypt('password')],
        );
    }
}
