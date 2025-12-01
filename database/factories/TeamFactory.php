<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->company(),
            'descripcion' => fake()->paragraph(),
            'posicion' => fake()->randomDigit(),
            'url_banner' => fake()->imageUrl(),
            'event_id' => Event::all()->random()->id,
        ];
    }
}
