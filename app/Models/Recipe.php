<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipe extends Model
{
    protected $fillable = ['title', 'instructions', 'total_calories'];

    protected $casts = [
        'total_calories' => 'decimal:2',
        'calculated_proteins' => 'decimal:2',
        'calculated_carbs' => 'decimal:2',
        'calculated_fats' => 'decimal:2',
        'created_at' => 'datetime:d-m-Y H:i',
        'updated_at' => 'datetime:d-m-Y H:i',
    ];
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_recipe')
                    ->withPivot('amount') //la cantidad de la tabla intermedia
                    ->withTimestamps();
    }
}
