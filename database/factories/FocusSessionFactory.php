<?php

namespace Database\Factories;

use App\Enums\FocusSessionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FocusSession>
 */
class FocusSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'duration' => fake()->randomElement([15, 25, 45, 60, 90, 120]),
            'status' => fake()->randomElement(['completed', 'completed', 'completed', 'interrupted']),
            'date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
        ];
    }

    public function completed(): static
    {
        return $this->state(['status' => FocusSessionStatus::COMPLETED]);
    }

    public function interrupted(): static
    {
        return $this->state(['status' => FocusSessionStatus::INTERRUPTED]);
    }
}
