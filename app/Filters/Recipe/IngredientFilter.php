<?php 

namespace App\Filters\Recipe; 

use App\Filters\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class IngredientFilter implements Filter
{
    public function apply(Builder $builder, $value)
    {
        // El whereHas es la relación 'ingredients'
        return $builder->whereHas('ingredients', function($q) use ($value) {
            // Esta consulta ocurre dentro de la tabla de ingredientes
            $q->where('name', 'like', "%{$value}%");
        });
    }
}