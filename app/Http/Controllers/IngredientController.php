<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredients = Ingredient::all();
        return response()->json($ingredients, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'calories_per_gram' => 'required|numeric|min:0',
            'protein' => 'required|numeric|min:0',
            'carbs' => 'required|numeric|min:0',
            'fats' => 'required|numeric|min:0',
        ]);
        
        $ingredient = Ingredient::create($request->all());

        return response()->json([
            'message' => 'Ingredient created successfully',
            'data' => $ingredient
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ingredient = Ingredient::with('recipes')->find($id);

        if (!$ingredient) {
        return response()->json(['message' => 'Ingredient not found'], 404);
    }

    return response()->json($ingredient, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ingredient = Ingredient::find($id);
        if(!$ingredient){
            return response()->json(['message' => 'Ingredient not found'], 404);
        }
        $request->validate([
            'name' => 'string|max:255',
            'calories_per_gram' => 'numeric',
            'protein' => 'numeric',
            'carbs' => 'numeric',
            'fats' => 'numeric',
        ]);
        $ingredient->update($request->all());
        return response()->json(['message' => 'Ingredient updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ingredient = Ingredient::find($id);
        if(!$ingredient){
            return response()->json(['message' => 'Ingredient not found'], 404);
        }
        $ingredient->recipes()->detach();
        
        $ingredient->delete();
        return response()->json(['message' => 'Ingredient deleted successfully'], 200);
    }
}
