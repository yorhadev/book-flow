<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;

class UserResource extends BaseResource
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
            'email' => $this->email,
            'registration_number' => $this->registration_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
