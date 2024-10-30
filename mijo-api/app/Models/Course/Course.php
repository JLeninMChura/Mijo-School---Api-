<?php

namespace App\Models\Course;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Sale\Review;
use App\Models\CoursesStudent;
use App\Models\Discount\DiscountCourse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "title",
        "subtitle",
        "slug",
        "imagen",
        "precio_usd",
        "precio_pen",
        "category_id",
        "sub_categorie_id",
        "user_id",
        "level",
        "idioma",
        "vimeo_id",
        "time",
        "description",
        "requirements",
        "what_is_it_for",
        "state",
    ];

    //protected $dates = ['deleted_at'];

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

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function sub_categorie()
    {
        return $this->belongsTo(Category::class);
    }

    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }

    public function discount_courses()
    {
        return $this->hasMany(DiscountCourse::class);
    }

    public function courses_students()
    {
        return $this->hasMany(CoursesStudent::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getDiscountCAttribute()
    {
        date_default_timezone_set("America/Lima");
        $discount = null;
        foreach ($this->category->discount_categories as $key => $discount_categorie) {
           if($discount_categorie->discount->type_campaing == 1 && $discount_categorie->discount->state == 1){
            if(Carbon::now()->between($discount_categorie->discount->start_date,Carbon::parse($discount_categorie->discount->end_date)->addDays(1))){
                // EXISTE UNA CAMPAÑA DE DESCUENTO CON EL CURSO
                $discount = $discount_categorie->discount;
                break;
            }
           }
        }
        return $discount;
    }

    public function getDiscountCTAttribute()
{
    date_default_timezone_set("America/Lima");
    $discount = null;

    foreach ($this->category->discount_categories as $key => $discount_categorie) {
        if (optional($discount_categorie->discount)->type_campaing == 1 && 
            optional($discount_categorie->discount)->state == 1) {
            
            if (Carbon::now()->between(
                optional($discount_categorie->discount)->start_date,
                Carbon::parse(optional($discount_categorie->discount)->end_date)->addDays(1)
            )) {
                $discount = $discount_categorie->discount;
                break;
            }
        }
    }
    return $discount;
}

    public function getFilesCountAttribute()
    {
        $files_count = 0;

        foreach ($this->sections as $keyS => $section) {
            foreach ($section->clases as $keyC => $clase) {
                $files_count += $clase->files->count();
            }
        }

        return $files_count;
    }

    function AddTimes($horas)
{
    $total = 0;
    foreach ($horas as $h) {
        $parts = array_reverse(explode(":", $h));
        $total += isset($parts[0]) ? intval($parts[0]) : 0; // segundos
        $total += isset($parts[1]) ? intval($parts[1]) * 60 : 0; // minutos
        $total += isset($parts[2]) ? intval($parts[2]) * 3600 : 0; // horas
    }
    $hours = floor($total / 3600);
    $minutes = floor(($total / 60) % 60);

    return $hours . " hrs " . $minutes . " mins";
}

    public function getCountClassAttribute()
    {
        $num = 0;
        foreach ($this->sections as $key => $section) {
            $num += $section->clases->count();
        }
        return $num;
    }

    public function getTimeCourseAttribute()
    {
        $times = [];
        foreach ($this->sections as $keyS => $section) {
            foreach ($section->clases as $keyc => $clase) {
                array_push($times, $clase->time);
            }
        }
        return $this->AddTimes($times);
    }

    public function getCountStudentsAttribute()
    {
        return $this->courses_students->count();
    }

    public function getCountReviewsAttribute()
    {
        return $this->reviews->count();
    }

    public function getAvgReviewsAttribute()
    {
        return $this->reviews->avg("rating");
    }

    function scopeFilterAdvance($query, $search, $state)
    {
        if ($search) {
            $query->where("title", "like", "%" . $search . "%");
        }
        if ($state) {
            $query->where("state", $state);
        }
        return $query;
    }

    function scopeFilterAdvanceEcommerce($query,$search,$selected_categories = [],$instructores_selected = [],
                                        $min_price = 0,$max_price = 0,$idiomas_selected = [],$levels_selected = [],
                                        $courses_a = [],$rating_selected = 0)
    {
        if($search){
            $query->where("title","like","%".$search."%");
        }
        if(sizeof($selected_categories) > 0){
            $query->whereIn("category_id",$selected_categories);
        }
        if(sizeof($instructores_selected) > 0){
            $query->whereIn("user_id",$instructores_selected);
        }
        if($min_price > 0 && $max_price > 0){
            $query->whereBetween("precio_usd",[$min_price,$max_price]);
        }
        if(sizeof($idiomas_selected) > 0){
            $query->whereIn("idioma",$idiomas_selected);
        }
        if(sizeof($levels_selected) > 0){
            $query->whereIn("level",$levels_selected);
        }
        if($courses_a || $rating_selected){
            $query->whereIn("id",$courses_a);
        }
        return $query;
    }
}