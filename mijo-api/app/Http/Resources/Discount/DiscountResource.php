<?php

namespace App\Http\Resources\Discount;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->resource->id,
            "code" => $this->resource->code,
            "type_discount" => $this->resource->type_discount,// 1 es % y 2 es monto fijo
            "discount" => $this->resource->discount,//el monto de descuento
            "start_date" => Carbon::parse($this->resource->start_date)->format("Y-m-d"),// 1 es ilimitado y 2 es limitado
            "end_date" => Carbon::parse($this->resource->end_date)->format("Y-m-d"),// el numero de usos permitidos
            "discount_type" => $this->resource->discount_type, // 1 es por productos y 2 es por categorias
            "type_campaing" => $this->resource->type_campaing,
            "state" => $this->resource->state ?? 1,
            "courses" => $this->resource->courses->map(function($course_axu) {
                return [
                    "id" => $course_axu->course->id,
                    "title" => $course_axu->course->title,
                    "imagen" => env("APP_URL")."storage/".$course_axu->course->imagen,
                    "axu_id" => $course_axu->id,
                ];
            }),
            "categories" => $this->resource->categories->map(function($categorie_axu) {
                return [
                    "id" => $categorie_axu->category?->id,
                    "name" => $categorie_axu->category?->name,
                    "imagen" => env("APP_URL")."storage/".$categorie_axu->category?->imagen,
                    "axu_id" => $categorie_axu->id,
                ];
            }),
        ];
    }
}