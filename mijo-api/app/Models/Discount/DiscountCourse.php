<?php

namespace App\Models\Discount;

use Carbon\Carbon;
use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscountCourse extends Model
{
    use HasFactory;
    protected $fillable = [
        "discount_id",
        "course_id",
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
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}