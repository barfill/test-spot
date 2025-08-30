<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dashboard;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DashboardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_by' => User::where('type', 'teacher')->inRandomOrder()->value('id') ?? User::factory(),
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'image_path' => fake()->imageUrl(),
            'image_disk' => fake()->randomElement(['local', 'public']),
            'is_active' => true,
        ];
    }
}
