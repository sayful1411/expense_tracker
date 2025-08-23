<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Expense;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create fixed categories if not exist
        $categories = ['Food', 'Transport', 'Shopping', 'Others'];
        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category]);
        }

        // Create users if not exist
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );
        User::firstOrCreate(
            ['email' => 'test2@example.com'],
            ['name' => 'Test User 2', 'password' => bcrypt('password')]
        );

        // Only create expenses if table is empty
        if (Expense::count() === 0) {
            Expense::factory(50)->create();
        }
    }
}
