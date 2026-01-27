<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Ingredient;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $salad = Recipe::create([
            'title' => 'Quinoa Power Salad',
            'instructions' => 'Cook the quinoa, add diced avocado and a poached egg on top.',
            'total_calories' => 0 
        ]);


        $quinoa = Ingredient::where('name', 'Quinoa')->first();
        $avocado = Ingredient::where('name', 'Avocado')->first();
        $egg = Ingredient::where('name', 'Egg')->first();


        $salad->ingredients()->attach($quinoa->id, ['amount' => 150]);
        $salad->ingredients()->attach($avocado->id, ['amount' => 50]);
        $salad->ingredients()->attach($egg->id, ['amount' => 50]);
    }
}
