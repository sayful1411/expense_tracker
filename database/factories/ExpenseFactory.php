<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => fn (array $attributes) => Category::factory()->for(
                $attributes['user_id'] instanceof Model ? $attributes['user_id'] : User::findOrFail($attributes['user_id'])
            ),
            'title' => $this->faker->sentence(3),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'date' => $this->faker->date(),
        ];
    }
}
