<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    protected $message;

    protected $errors = [];

    public function __construct($resource, $message = '', $errors = [])
    {
        parent::__construct($resource);

        $this->message = $message;

        $this->errors = $errors;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = [
            'data' => $this->collection,
            'message' => $this->message
        ];

        if (!empty($this->errors)) {
            $response['errors'] = $this->errors;
        }

        return $response;
    }
}
