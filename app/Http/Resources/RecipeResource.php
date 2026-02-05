<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'ingredients' => $this->whenLoaded('ingredients'),
            'nutritional_summary' => [
                'proteins' => number_format($this->calculated_proteins, 2, '.', '.'),
                'fats' => number_format($this->calculated_fats , 2, '.', '.'),
                'calories' => $this->total_calories,
                'health_tags' => $this->generateHealthTags(), 
            ],
        ];    
    }

    private function generateHealthTags(): array {
        $tags = [];
        if ($this->calculated_proteins > 20) $tags[] = 'High Protein';
        if ($this->total_calories < 400) $tags[] = 'Low Calorie';
        if($this->calculated_carbs < 100) $tags[] = 'Low Carbs';
        if($this->calculated_fats > 200) $tags[] = 'High Fats';

        return $tags;
    }
}
