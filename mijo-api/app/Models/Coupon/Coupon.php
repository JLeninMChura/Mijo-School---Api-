<?php

namespace App\Models\Coupon;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "code",
        "type_discount",// 1 es % y 2 es monto fijo
        "discount",//el monto de descuento
        "type_count",// 1 es ilimitado y 2 es limitado
        "num_use",// el numero de usos permitidos
        "type_coupon" , // 1 es por productos y 2 es por categorias
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
       return $this->hasMany(CouponCourse::class);
    }

    public function categories()
    {
       return $this->hasMany(CouponCategorie::class);
    }

    function scopeFilterAdvance($query,$search,$state)
    {
        if($search){
            $query->where("code","like","%".$search."%");
        }
        if($state){
            $query->where("state",$state);
        }
        
        return $query;
    }
}