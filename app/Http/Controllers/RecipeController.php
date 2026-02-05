<?php

namespace App\Http\Controllers;

use App\Filters\RecipeFilters;
use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, RecipeFilters $filters)
    {
        $query = Recipe::query();
    
        $query = $filters->apply($query);
    
        // ordenamiento
        $sort = $request->query('sort', 'created_at');
        $order = $request->query('order', 'desc');
    
        $allowedSorts = ['title', 'total_calories', 'calculated_proteins', 'created_at'];
        
        // Si NO está en los permitidos, por defecto: 'created_at'
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
    
        $query->orderBy($sort, $order === 'asc' ? 'asc' : 'desc');
    
        // paginación 
        $recipes = $query->paginate($request->query('size', 10))->withQueryString(); //para mantener los filtros en la siguiente pagina
    
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
        $recipe->load('ingredients');
        $this->updateNutritionalValues($recipe);

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

        $health_tags = [];

        if ($recipe->calculated_proteins > 20) {
            $health_tags[] = 'High Protein';
        }

        if ($recipe->total_calories < 400) {
            $health_tags[] = 'Low Calorie';
        }

        if ($recipe->calculated_fats < 10) {
            $health_tags[] = 'Low Fat';
        }

        $recipe->nutritional_summary = [
            'proteins' => number_format($recipe->calculated_proteins, 2, '.', '.'),
            'fats' => number_format($recipe->calculated_fats , 2, '.', '.'),
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

    private function updateNutritionalValues(Recipe $recipe)
    {
        $recipe->refresh(); 
        $ingredients = $recipe->ingredients;

        $totalProteins = 0;
        $totalCalories = 0;
        $totalCarbs = 0;
        $totalFats = 0;

        foreach ($recipe->ingredients as $ingredient) {
            $amount = $ingredient->pivot->amount;
            $totalCalories += ($ingredient->calories_per_gram * $amount);
            $totalProteins += ($ingredient->protein * ($amount / 100));
            $totalCarbs += ($ingredient->carbs * ($amount / 100));
            $totalFats += ($ingredient->fats * ($amount / 100));
        }

        // Guardamos en la base de datos
        $recipe->update([
            'total_calories' => round($totalCalories, 2),
            'calculated_proteins' => round($totalProteins, 2),
            'calculated_carbs' => round($totalCarbs, 2),
            'calculated_fats' => round($totalFats, 2),
        ]);
    }
}
