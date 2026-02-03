<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recipes = Recipe::all();
        return response()->json($recipes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'required|string',
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.amount' => 'required|numeric|min:0'
        ]);
    

        $recipe = Recipe::create($request->only(['title', 'instructions']));
    

        foreach ($request->ingredients as $item) {
            $recipe->ingredients()->attach($item['id'], ['amount' => $item['amount']]);
        }
    

        return response()->json([
            'message' => 'Recipe and ingredients linked successfully',
            'data' => $recipe->load('ingredients')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $recipe = Recipe::with('ingredients')->find($id);

        if (!$recipe) {
            return response()->json(['message' => 'Recipe not found'], 404);
        }

        $totalProteins = 0;
        $totalCalories = 0;
        $totalCarbs = 0;
        $totalFats = 0;

        foreach ($recipe->ingredients as $ingredient) {
            $amount = $ingredient->pivot->amount; // Cantidad en gramos
            $totalCalories += ($ingredient->calories_per_gram * $amount);
            // Proteínas, Carbos y Grasas suelen tener valor nutricional
            // cada 100g, por eso divido la cantidad por 100
            $factor = $amount / 100;

            $totalProteins += ($ingredient->protein * $factor);
            $totalCarbs += ($ingredient->carbs * $factor);
            $totalFats += ($ingredient->fats * $factor);
        }

        // Agregamos los totales al objeto antes de mandarlo
        $recipe->total_calories = round($totalCalories,2);
        $recipe->calculated_proteins = round($totalProteins,2);
        $recipe->calculated_carbs = round($totalCarbs,2);
        $recipe->calculated_fats = round($totalFats,2);
        $recipe->save(); //para guardar el valor en la db y no calcularlo cada vez

        $health_tags = [];

        if ($totalProteins > 20) {
            $health_tags[] = 'High Protein';
        }

        if ($totalCalories < 400) {
            $health_tags[] = 'Low Calorie';
        }

        if ($totalFats < 10) {
            $health_tags[] = 'Low Fat';
        }

        $recipe->nutritional_summary = [
            'proteins' => number_format($totalProteins, 2, '.', '.'),
            'fats' => number_format($totalFats, 2, '.', '.'),
            'health_labels' => $health_tags
        ];
        return response()->json($recipe, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $recipe = Recipe::find($id);
        if(!$recipe){
            return response()->json(['message' => 'Recipe not Found'], 404);
        }
        $request->validate([
            'title' => 'string|max:255',
            'instructions' => 'string', 
        ]);
        $recipe->update($request->all());
        return response()->json(['message' => 'Recipe updated successfully '], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $recipe = Recipe::find($id);
        if(!$recipe){
            return response()->json(['message' => 'Recipe not Found'], 404);
        }
        $recipe->delete();
        return response()->json(['message' => 'Recipe deleted successfully'], 200);
    }
}
