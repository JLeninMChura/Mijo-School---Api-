<?php

namespace App\Models\Course;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSection extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "name",
        "course_id",
        "state"
    ];
    public function setCreatedAtAttribute($value)
    {
        date_default_timezone_set("America/Lima");
        $this->attributes["created_at"] = Carbon::now();
    }

    public function setUpdateAtAttribute($value)
    {
        date_default_timezone_set("America/Lima");
        $this->attributes["updated_at"] = Carbon::now();
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function clases()
    {
        return $this->hasMany(CourseClase::class, "course_section_id");
    }


    function AddTimes($horas)
    {
        $total = 0;
        foreach ($horas as $h) {
            $parts = explode(":", $h);
            $total += $parts[2] + $parts[1] * 60 + $parts[0] * 3600;
        }
        $hours = floor($total / 3600);
        $minutes = floor(($total / 60) % 60);
        $seconds = $total % 60;

        return $hours . " hrs " . $minutes . " mins";
    }
    public function getTimeSectionAttribute()
    {
        $times = [];
        foreach ($this->clases as $keyc => $clase) {
            array_push($times, $clase->time);
        }

        return $this->AddTimes($times);
    }
}
