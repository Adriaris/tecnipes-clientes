<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use App\Models\Cliente;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bascula>
 */
class BasculaFactory extends Factory
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
            'instrumento' => $faker->optional()->word,
            'indicador' => $faker->optional()->word,
            'fabricante' => $faker->optional()->company,
            'modelo' => $faker->optional()->word,
            'numero_serie' => $faker->optional()->bothify('##??######'),
            'codigo' => $faker->optional()->bothify('#####???'),
            'ubicacion' => $faker->optional()->address,
            'maximo' => $faker->optional()->numberBetween(1, 10000),
            'unidad_medida_kg_g' => $faker->optional()->randomElement(['kg', 'g']),
            'minimo' => $faker->optional()->numberBetween(1, 10000),
            'escalon' => $faker->optional()->word,
            'divisiones' => $faker->optional()->numberBetween(1, 1000),
            'acabado' => $faker->optional()->word,
            'instalacion' => $faker->optional()->word,
            'dimensiones' => $faker->optional()->word,
            'numero_apoyos' => $faker->optional()->numberBetween(1, 10),
            'tipo_apoyo' => $faker->optional()->word,
            'modulo_celula' => $faker->optional()->company,
            'cap_celula' => $faker->optional()->word,
            'nota_bascula' => $faker->optional()->text(1000),
            'id_cliente' => Cliente::inRandomOrder()->first()->id, // Asignar un cliente aleatorio
        ];
    }
}
