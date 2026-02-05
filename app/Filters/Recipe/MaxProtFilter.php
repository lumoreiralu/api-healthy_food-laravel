<?php

namespace App\Filters\Recipe;

use App\Filters\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class MaxProtFilter implements Filter{
    /**
     * * @param Builder $builder
     * @param mixed $value 
     * @return Builder
     */

     public function apply(Builder $builder, $value){
        return $builder->where('calculated_proteins', '<=', $value);
     }
}