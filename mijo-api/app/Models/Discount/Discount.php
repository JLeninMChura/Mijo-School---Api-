<?php

namespace App\Models\Discount;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "code",
        "type_discount",// 1 es % y 2 es monto fijo
        "discount",//el monto de descuento
        "start_date",// 1 es ilimitado y 2 es limitado
        "end_date",// el numero de usos permitidos
        "discount_type" , // 1 es por productos y 2 es por categorias
        "type_campaing",
        "state",
    ];

    public function setCreatedAtAttribute($value)
    {
        date_default_timezone_set("America/Lima");
        $this->attributes["created_at"] = Carbon::now();
    }

    public function setUpdatedAtAttribute($value)
    {
        date_default_timezone_set("America/Lima");
        $this->attributes["updated_at"] = Carbon::now();
    }

    public function courses()
    {
       return $this->hasMany(DiscountCourse::class);
    }

    public function categories()
    {
       return $this->hasMany(DiscountCategorie::class);
    }

    function scopeFilterAdvance($query,$state)
    {
        // if($search){
        //     $query->where("code","like","%".$search."%");
        // }
        if($state){
            $query->where("state",$state);
        }
        
        return $query;
    }
}