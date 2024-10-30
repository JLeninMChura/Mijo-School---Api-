<?php

namespace App\Http\Resources\Ecommerce\Sale;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            "method_payment" => $this->resource->method_payment,
            "currency_payment" => $this->resource->currency_payment,
            "total" => $this->resource->id,
            "n_transaccion" => $this->resource->n_transaccion,
            "sale_details" => $this->resource->sale_details->map(function($sale_detail){
                return [
                    "id" => $sale_detail->id,
                    "course" => [
                        "id" => $sale_detail->course->id,
                        "title" => $sale_detail->course->title,
                        "imagen" => env("APP_URL")."storage/".$sale_detail->course->imagen,
                    ],
                    "type_discount" => $sale_detail->type_discount,
                    "discount" => $sale_detail->discount,
                    "type_campaing" => $sale_detail->type_campaing,
                    "code_cupon" => $sale_detail->code_cupon,
                    "code_discount" => $sale_detail->code_discount,
                    "precio_unitario" => $sale_detail->precio_unitario,
                    "total" => $sale_detail->total,
                    "created_at" => $sale_detail->created_at->format("Y-m-d h:i:s"),
                ];
            }),
            "created_at" => $this->resource->created_at->format("Y-m-d h:i:s"),
        ];
    }
}
