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
            'user_id' => $this->babyProfile?->user_id ?? null,
            'log_type' => $this->type, // Maps 'type' to 'log_type'
            'milk_type' => $this->milk_type,
            'volume' => $this->volume_ml, // Maps 'volume_ml' to 'volume'
            'food_name' => $this->food_name,
            'poop_type' => $this->poop_type,
            'notes' => $this->notes,
            'created_at' => $this->logged_at?->toIso8601String() ?? $this->created_at?->toIso8601String(),
        ];
    }
}
