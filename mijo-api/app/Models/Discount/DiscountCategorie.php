<?php

namespace App\Models\Discount;

use Carbon\Carbon;
use App\Models\Course\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscountCategorie extends Model
{
    use HasFactory;
    protected $fillable = [
        "discount_id",
        "category_id",
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
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}