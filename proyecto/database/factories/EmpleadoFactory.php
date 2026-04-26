<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empleado>
 */
class EmpleadoFactory extends Factory
{

    public function definition(): array
    {
        return [
            'dni' => fake()->unique()->numerify('########?'),
            'nombre' => fake()->name(),
            'correo' => fake()->unique()->safeEmail(),
            'telefono' => fake()->phoneNumber(),
            'direccion' => fake()->address(),
            'fecha_alta' => fake()->date(),
            'tipo' => fake()->randomElement(['admin', 'operario']),
            'password' => Hash::make('password'), // Contraseña por defecto
        ];
    }
}