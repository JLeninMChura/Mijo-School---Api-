<?php

namespace App\Models\Coupon;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Course\Category;

class CouponCategorie extends Model
{
    use HasFactory;
    protected $fillable = [
        "coupon_id",
        "categorie_id",
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
    
    public function categorie()
    {
        return $this->belongsTo(Category::class);
    }
}