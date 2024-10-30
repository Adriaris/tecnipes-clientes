<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();
        return [
            'nombre' => $faker->name,
            'horario' => $faker->optional()->text(150),
            'direccion_1' => $faker->streetAddress,
            'direccion_2' => $faker->optional()->streetAddress,
            'direccion_3' => $faker->optional()->streetAddress,
            'telefono' => $faker->optional()->numerify('#########'),
            'persona_contacto' => $faker->optional()->name,
            'telefono_persona_contacto' => $faker->optional()->numerify('#########'),
            'nota_cliente' => $faker->optional()->text(1000),
            'gps' => $faker->optional()->text(255),
        ];
    }
}
