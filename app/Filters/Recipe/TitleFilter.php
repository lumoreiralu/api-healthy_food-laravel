<?php

namespace App\Filters\Recipe;

use App\Filters\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class TitleFilter implements Filter
{
    /**
     * Aplicar el filtro de título a la consulta.
     * * @param Builder $builder
     * @param mixed $value  <--lo que el usuario escribió (ej: "Ensalada")
     * @return Builder
     */
    public function apply(Builder $builder, $value)
    {
        // El 'when' ya se evaluó, aca solo se aplica el where
        return $builder->where('title', 'like', "%{$value}%");
    }
}