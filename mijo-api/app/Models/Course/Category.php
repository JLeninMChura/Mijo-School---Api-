<?php


namespace App\Models\Course;

use App\Models\Discount\DiscountCategorie;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    // protected $dates = ['deleted_at'];
    protected $fillable = [
        "name",
        "imagen",
        "category_id",
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

    public function children()
    {
        return $this->hasMany(Category::class, "category_id");
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function discount_categories()
    {
        return $this->hasMany(DiscountCategorie::class);
    }

    public function father()
    {
        return $this->belongsTo(Category::class, "category_id");
    }
    function scopeFilterAdvance($query, $search, $state)
    {
        if ($search) {
            $query->where("name", "like", "%" . $search . "%");
        }
        if ($state) {
            $query->where("state", $state);
        }
        return $query;
    }
}
