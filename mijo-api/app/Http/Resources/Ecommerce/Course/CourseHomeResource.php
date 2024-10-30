<?php

namespace App\Http\Resources\Ecommerce\Course;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseHomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Es la campaña de descuento con la que esta relacionada
        $discount_g = null;
        // if(){ // CURSO TIENE DESCUENTO A NIVEL (DE CURSO Y A NIVEL CATEGORIA)
            // $discount_g = AL DESCUENTO DE TIPO CATEGORIA
        // }else{
        //     if(){ // CURSO TIENE DESCUENTO A NIVEL DE CURSO Y NO  ANIVEL DE CATEGORIA
            // $discount_g = AL DESCUENTO DE TIPO CURSO
        //     }else{
                //  if(){ // CURSO NO TIENE DESCUENTO A NIVEL DE CURSO  , PERO SI TIENE DESCUENTO A NIVEL DE CATEGORIA
                    // $discount_g = AL DESCUENTO DE TIPO CATEGORIA
                //  }
        //     }
        // }
        if($this->resource->discount_c && $this->resource->discount_c_t){
            $discount_g = $this->resource->discount_c_t;
        }else{
            if($this->resource->discount_c && !$this->resource->discount_c_t){
                $discount_g = $this->resource->discount_c;
            }else{
                if(!$this->resource->discount_c && $this->resource->discount_c_t){
                    $discount_g = $this->resource->discount_c_t;
                }
            }
        }
        return [
            "id" => $this->resource->id,
            "title" => $this->resource->title,
            "slug" => $this->resource->slug,
            "subtitle" => $this->resource->subtitle,
            "imagen" => env("APP_URL")."storage/".$this->resource->imagen,
            "precio_usd" => $this->resource->precio_usd,
            "precio_pen" => $this->resource->precio_pen,
            "count_class" => $this->resource->count_class,
            "time_course" => $this->resource->time_course,
            "discount_g" => $discount_g,
            "count_students" => $this->resource->count_students,
            "avg_reviews" => $this->resource->avg_reviews ? round($this->resource->avg_reviews,2): 0,
            "count_reviews" => $this->resource->count_reviews,
            "instructor" => $this->resource->instructor ? [
                "id" => $this->resource->instructor->id,
                "full_name" => $this->resource->instructor->name. ' '. $this->resource->instructor->surname,
                "avatar" => env("APP_URL")."storage/".$this->resource->instructor->avatar,
                "profesion" => $this->resource->instructor->profesion,
            ] : NULL
        ];
    }
}
