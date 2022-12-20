<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->firstName() . ' ' . fake()->lastName,
            'birth_date' => now(),
            'user_id' => fake()->numberBetween(1, 50),
            'created_at' => now()
        ];
    }
}
