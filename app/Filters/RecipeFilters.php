<?php

namespace App\Filters;

use App\Filters\Recipe\TitleFilter;
use App\Filters\Recipe\IngredientFilter;
use App\Filters\Recipe\MinCalFilter;
use App\Filters\Recipe\MaxCalFilter;
use App\Filters\Recipe\MaxCarbFilter;
use App\Filters\Recipe\MinCarbFilter;
use App\Filters\Recipe\MaxFatFilter;
use App\Filters\Recipe\MinFatFilter;
use App\Filters\Recipe\MaxProtFilter;
use App\Filters\Recipe\MinProtFilter;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class RecipeFilters
{
    protected $request;

    // mapeo el nombre que viene en la URL con la clase que lo maneja
    protected $filters = [
        //nombre para buscar en la url
        'title'      => TitleFilter::class,
        'ingredient' => IngredientFilter::class,
        'min_cal'    => MinCalFilter::class,
        'max_cal'    => MaxCalFilter::class,
        'min_prot'    => MinProtFilter::class,
        'max_prot'    => MaxProtFilter::class,
        'min_fat'    => MinFatFilter::class,
        'max_fat'    => MaxFatFilter::class,
        'min_carb'    => MinCarbFilter::class,
        'max_carb'    => MaxCarbFilter::class,

    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        // recorro todos los filtros definidos arriba
        foreach ($this->filters as $name => $filterClass) {
            // Si el usuario envió ese parámetro en la URL...
            if ($this->request->has($name) && !empty($this->request->get($name))) {
                // Instanciamos la clase y aplicamos el filtro
                (new $filterClass)->apply($builder, $this->request->get($name));
            }
        }

        return $builder;
    }
}