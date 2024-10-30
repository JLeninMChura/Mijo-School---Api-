<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserGResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            "id" => $this->resource->id,
            "name" => $this->resource->name,
            "surname" =>  $this->resource->surname,
            "email" =>  $this->resource->email,
            "role" =>  $this->resource->role,
            "state" =>  $this->resource->state,
            "is_instructor" =>  $this->resource->is_instructor,
            "profesion" =>  $this->resource->profesion,
            "description" =>  $this->resource->description,
            "created_at" =>  $this->resource->created_at->format("Y-m-d h:i:s"),
            "avatar" => env("APP_URL") . "storage/" . $this->resource->avatar,
        ];
    }
}
