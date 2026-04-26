<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define el estatus
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cif' => fake()->unique()->numerify('A########'),
            'nombre' => fake()->company(),
            'telefono' => fake()->phoneNumber(),
            'correo' => fake()->unique()->safeEmail(),
            'cuenta_corriente' => fake()->iban('ES'),
            'pais' => fake()->country(),
            'moneda' => 'EUR',
            'importe_cuota_mensual' => fake()->randomFloat(2, 50, 500),
        ];
    }
}