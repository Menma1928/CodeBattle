<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->sentence(3),
            'descripcion' => fake()->paragraph(),
            'fecha_inicio' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'fecha_fin' => fake()->dateTimeBetween('+1 month', '+3 months'),
            'direccion' => fake()->address(),
            'estado' => fake()->randomElement(['activo', 'inactivo', 'finalizado']),
            'url_imagen' => 'https://placehold.co/600x600',
            'admin_id'=> User::all()->random()->id,

        ];
    }
}
