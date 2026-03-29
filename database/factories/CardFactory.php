<?php

namespace Database\Factories;

use App\Enums\CardRarity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(2, true),
            'rarity' => $this->faker->randomElement(['commun','uncommun','rare','epic','legendary']),
            'image'  => fake()->imageUrl(640, 480, 'cards', true),
        ];
    }
}
