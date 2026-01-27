<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            ['name' => 'Avocado', 'calories_per_gram' => 1.6, 'protein' => 0.02, 'carbs' => 0.09, 'fats' => 0.15],
            ['name' => 'Chicken Breast', 'calories_per_gram' => 1.65, 'protein' => 0.31, 'carbs' => 0.00, 'fats' => 0.04],
            ['name' => 'Quinoa', 'calories_per_gram' => 1.2, 'protein' => 0.04, 'carbs' => 0.21, 'fats' => 0.02],
            ['name' => 'Egg', 'calories_per_gram' => 1.55, 'protein' => 0.13, 'carbs' => 0.01, 'fats' => 0.11],
            ['name' => 'Chia Seeds', 'calories_per_gram' => 4.8, 'protein' => 0.17, 'carbs' => 0.42, 'fats' => 0.31],
        ];
    
        foreach ($ingredients as $ingredient) {
            \App\Models\Ingredient::create($ingredient);
        }
    }
}
