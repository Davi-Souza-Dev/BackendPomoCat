<?php

namespace Database\Factories;

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
        // Geramos uma data aleatória entre o primeiro dia deste mês e hoje
        $randomDate = fake()->dateTimeBetween('first day of this month', 'now');

        return [
            'user_id' => 1, // Fixo conforme solicitado
            'duration' => fake()->randomElement([15, 25, 45, 60, 90, 120]), // Duração em minutos
            'status' => fake()->randomElement(['completed', 'completed', 'completed', 'interrupted']), // 75% de chance de completado para ajudar nas streaks
            'date' => $randomDate->format('Y-m-d'), // Salvando como string no formato Y-m-d
        ];
    }
}
