<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Bascula;


class BasculasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(Bascula::class, 15000)->create();
        //Factory::factoryForModel(App\Models\Bascula::class)->count(15000)->create(); // Crear 15000 registros de basculas

        Bascula::factory()->count(6000)->create();
    }
}

