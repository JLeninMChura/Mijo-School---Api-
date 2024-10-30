<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseGResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->resource->id,
            "title" => $this->resource->title,
            "subtitle" => $this->resource->subtitle,
            "slug" => $this->resource->slug,
            "imagen" => env("APP_URL") . "storage/" . $this->resource->imagen,
            "precio_usd" => $this->resource->precio_usd,
            "precio_pen" => $this->resource->precio_pen,
            "category_id" => $this->resource->category_id,
            "category" => [
                "id" => $this->resource->category->id,
                "name" => $this->resource->category->name,
            ],
            "sub_categorie_id" => [
                "id" => $this->resource->sub_categorie->id,
                "name" => $this->resource->sub_categorie->name,
            ],
            "sub_categorie" => $this->resource->sub_categorie,
            "user_id" => $this->resource->user_id,
            "user" => [
                "id" => $this->resource->instructor->id ? [] :null,
                "full_name" => $this->resource->instructor->name . ' ' . $this->resource->instructor->surname,
                "email" => $this->resource->instructor->email,
            ],
            "level" => $this->resource->level,
            "idioma" => $this->resource->idioma,
            "vimeo_id" => $this->resource->vimeo_id, ///
            "time" => $this->resource->time,
            "description" => $this->resource->description,
            "requirements" => json_decode($this->resource->requirements),
            "what_is_it_for" => json_decode($this->resource->what_is_it_for),
            "state" => $this->resource->state
        ];
    }
}
