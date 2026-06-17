<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,  // Keep original field name
            'milk_type' => $this->milk_type,
            'volume_ml' => $this->volume_ml,  // Keep original field name
            'food_name' => $this->food_name,
            'food_type' => $this->food_type,
            'texture' => $this->texture,
            'finish_level' => $this->finish_level,
            'poop_type' => $this->poop_type,
            'notes' => $this->notes,
            'logged_at' => $this->logged_at?->toIso8601String() ?? $this->created_at?->toIso8601String(),
        ];
    }
}
