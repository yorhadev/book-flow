<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends BaseResource
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
            'name' => $this->name,
            'author' => $this->author,
            'registration_number' => $this->registration_number,
            'status' => $this->status,
            'genres' => GenreResource::collection($this->genres), // Aqui pegamos os gêneros
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
