<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tarea>
 */
class TareaFactory extends Factory
{

    public function definition(): array
    {
        return [
            'persona_contacto' => fake()->name(),
            'telefono_contacto' => fake()->phoneNumber(),
            'descripcion' => fake()->sentence(),
            'correo_contacto' => fake()->safeEmail(),
            'direccion' => fake()->address(),
            'poblacion' => fake()->city(),
            'codigo_postal' => '21001',
            'provincia_ine' => '21',
            'estado' => 'P',
            'fecha_creacion' => now(),
        ];
    }
}