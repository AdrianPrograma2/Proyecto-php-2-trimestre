<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cuota>
 */
class CuotaFactory extends Factory
{

    public function definition(): array
    {
        return [
            'concepto' => fake()->sentence(3),
            'fecha_emision' => fake()->date(),
            'importe' => fake()->randomFloat(2, 10, 300),
            'pagada' => fake()->randomElement(['S', 'N']),
            'notas' => fake()->optional()->sentence(),
        ];
    }
}