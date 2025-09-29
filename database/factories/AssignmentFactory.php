<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Assignment;
use App\Models\Dashboard;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dashboard_id' => Dashboard::where('is_active', true)->inRandomOrder()->value('id') ?? Dashboard::factory(),
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'start_time' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'end_time' => fake()->dateTimeBetween('start_time', '+2 weeks'),
            'status' => fake()->randomElement(Assignment::$statuses),
        ];
    }
}
