<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\AuthController;
//usuario no logueados realizan login o registran nuevos usuarios
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/recipes', [RecipeController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //usuarios logueados pueden ver recetas e ingredientes y sus detalles

    Route::get('/recipes/{id}', [RecipeController::class, 'show']);
    Route::get('/ingredients', [IngredientController::class, 'index']);
    Route::get('/ingredients/{id}', [IngredientController::class, 'show']);
    //edita y crea solo user => admin/editor
    Route::post('/recipes', [RecipeController::class, 'store'])
    ->middleware('rol:admin,editor');
    Route::put('/recipes/{id}', [RecipeController::class, 'update'])
    ->middleware('rol:admin,editor');
    Route::post('/ingredients', [IngredientController::class, 'store'])
    ->middleware('rol:admin,editor');
    Route::put('/ingredients/{id}', [IngredientController::class, 'update'])
    ->middleware('rol:admin,editor');
   
    //borra solo el user => admin
    Route::delete('/recipes/{id}', [RecipeController::class, 'destroy'])
    ->middleware('rol:admin');
    Route::delete('/ingredients/{id}', action: [RecipeController::class, 'destroy'])
    ->middleware('rol:admin');


    


    //Si no quisiera separar las acciones 
    // Route::apiResource('recipes', RecipeController::class);
    // Route::apiResource('ingredients', IngredientController::class);
});

