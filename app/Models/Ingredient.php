<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    protected $fillable = ['name', 'calories_per_gram', 'protein', 'carbs', 'fats'];

    protected $casts = [
        'calories_per_gram' => 'decimal:2',
        'protein' => 'decimal:2',
        'carbs' => 'decimal:2',
        'fats' => 'decimal:2',
        'created_at' => 'datetime:d-m-Y H:i',
        'updated_at' => 'datetime:d-m-Y H:i',
    ];

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'ingredient_recipe')
                    ->withPivot('amount')
                    ->withTimestamps();
    }

}
