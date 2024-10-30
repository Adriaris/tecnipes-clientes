<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cliente;


class ClientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(Cliente::class, 5000)->create();
        //Factory::factoryForModel(App\Models\Cliente::class)->count(10)->create();
        Cliente::factory()->count(3000)->create();
    }
}
