<?php

namespace App\Http\Controllers\Tienda;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Models\CoursesStudent;
use App\Models\Course\Category;
use App\Models\Discount\Discount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Ecommerce\Course\CourseHomeResource;
use App\Http\Resources\Ecommerce\Course\CourseHomeCollection;
use App\Http\Resources\Ecommerce\LandingCourse\LandingCourseResource;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $categories = Category::where("category_id", null)->withCount("courses")->orderBy("id", "desc")->get();
        $courses = Course::where("state", 2)->inRandomOrder()->limit(3)->get();
        $categories_courses = Category::where("category_id", null)->withCount("courses")->having("courses_count", ">", 0)->orderBy("id", "desc")->take(5)->get();
        $group_courses_categories = collect([]);

        foreach ($categories_courses as $key => $categories_course) {
            $group_courses_categories->push(
                [
                    "id" => $categories_course->id,
                    "name" => $categories_course->name,
                    "name_empty" => str_replace(" ", "", $categories_course->name),
                    "courses_count" => $categories_course->course_count,
                    "courses" => CourseHomeCollection::make($categories_course->courses),
                ]
            );
        };

        date_default_timezone_set("America/Lima");
        $desconut_baner = Discount::where("type_campaing", 3)->where("state", 1)
            ->where("start_date", "<=", today())
            ->where("end_date", ">=", today())
            ->first();

        $discount_banner_course = collect([]);
        if ($desconut_baner) {
            foreach ($desconut_baner->courses as $key => $coursedis) {
                $discount_banner_course->push(CourseHomeResource::make($coursedis->course));
            }
        }

        date_default_timezone_set("America/Lima");
        $desconut_flash = Discount::where("type_campaing", 2)->where("state", 1)
            ->where("start_date", "<=", today())
            ->where("end_date", ">=", today())
            ->first();

        $desconut_flash_course = collect([]);
        if ($desconut_flash) {
            $desconut_flash->end_date = Carbon::parse($desconut_flash->end_date)->addDays(1);
            foreach ($desconut_flash->courses as $key => $coursedis) {
                $desconut_flash_course->push(CourseHomeResource::make($coursedis->course));
            }
        }
        return response()->json([
            "categories" => $categories->map(
                function ($categories) {
                    return [
                        "id" => $categories->id,
                        "name" => $categories->name,
                        "imagen" => env("APP_URL") . "storage/" . $categories->imagen,
                        "course_count" => $categories->courses_count,
                    ];
                }
            ),
            "courses_home" => CourseHomeCollection::make($courses),
            "group_courses_categories" => $group_courses_categories,
            "desconut_baner" => $desconut_baner,
            "discount_banner_course" => $discount_banner_course,
            "desconut_flash" => $desconut_flash ? [
                "id" => $desconut_flash->id,
                "discount" => $desconut_flash->discount,
                "code" => $desconut_flash->code,
                "type_campaing" => $desconut_flash->type_campaing,
                "type_discount" => $desconut_flash->type_discount,
                "end_date" => Carbon::parse($desconut_flash->end_date)->format("Y-m-d"),
                "start_date_d" => Carbon::parse($desconut_flash->start_date)->format("Y/m/d"),
                "end_date_d" => Carbon::parse($desconut_flash->end_date)->subDays(1)->format("Y/m/d"),
            ] : NULL,
            "desconut_flash_course" => $desconut_flash_course
        ]);
    }

    public function course_detail(Request $request,$slug)
    {
        $campaing_discount = $request->get("campaing_discount");
        $discount = null;
        if($campaing_discount){
            $discount = Discount::findOrFail($campaing_discount);
        }
        $course = Course::where("slug",$slug)->first();
        $is_have_course = false;
        if(!$course){
            return abort(404);
        }
        if(Auth::guard("api")->check()){
            $course_student = CoursesStudent::where("user_id",auth("api")->user()->id)->where("course_id",$course->id)->first();
            if($course_student){
                $is_have_course = true;
            }
        }
        $courses_related_instructor = Course::where("id","<>",$course->id)->where("user_id", $course->user_id)->inRandomOrder()->take(2)->get();

        $courses_related_categories = Course::where("id","<>",$course->id)->where("category_id", $course->category_id)->inRandomOrder()->take(3)->get();

        return response()->json([
            "course" => LandingCourseResource::make($course),
            "courses_related_instructor" => $courses_related_instructor->map(function($course){
                return CourseHomeResource::make($course);
            }),
            "courses_related_categories" => $courses_related_categories->map(function($course){
                return CourseHomeResource::make($course);
            }),
            "DISCOUNT" => $discount,
            "is_have_course" => $is_have_course,
        ]);
    }

    public function course_leason(Request $request,$slug)
    {

        $course = Course::where("slug",$slug)->first();

        if(!$course){
            return response()->json(["message" => 403, "message_text" => "EL CURSO NO EXISTE"]);
        }

        $course_student = CoursesStudent::where("course_id",$course->id)->where("user_id",auth("api")->user()->id)->first();
        if(!$course_student){
            return response()->json(["message" => 403, "message_text" => "TU NO ESTAS INSCRITO EN ESTE CURSO"]);
        }
        
        return response()->json([
            "course" => LandingCourseResource::make($course),
        ]);
    }
    
    public function listCourses(Request $request)
    {
        $search = $request->search;
        $selected_categories = $request->selected_categories ?? [];
        $instructores_selected = $request->instructores_selected ?? [];
        $min_price = $request->min_price;
        $max_price = $request->max_price;

        $idiomas_selected = $request->idiomas_selected ?? [];
        $levels_selected = $request->levels_selected ?? [];
        $rating_selected = $request->rating_selected;

        $courses_a = [];
        if($rating_selected){
            $courses_query = Course::where("state",2)
                         ->join("reviews","reviews.course_id" ,"=", "courses.id")
                         ->select("courses.id as courseId",DB::raw("AVG(reviews.rating) as rating_reviews"))
                         ->groupBy("courseId")
                         ->having("rating_reviews",">=",$rating_selected) // 3.6
                         ->having("rating_reviews","<",$rating_selected + 1)
                         ->get();
            $courses_a= $courses_query->pluck("courseId")->toArray();
            // error_log(json_encode($courses_a));
        }
        // if(!$search){
        //     return response()->json(["courses" => []]);
        // }
        $courses = Course::filterAdvanceEcommerce($search,
                            $selected_categories,
                            $instructores_selected,
                            $min_price,$max_price,
                            $idiomas_selected,$levels_selected,
                            $courses_a,$rating_selected)->where("state",2)->orderBy("id","desc")->get();

        return response()->json(["courses" => CourseHomeCollection::make($courses)]);
    }

    public function config_all()
    {
        $categories = Category::where("category_id",NULL)->withCount("courses")->orderBy("id","desc")->get();
        $instructores = User::where("is_instructor",1)->orderBy("id","desc")->get();
        return response()->json([
            "categories" => $categories,
            "instructores" => $instructores->map(function($user){
                return [
                    "id" => $user->id,
                    "courses_count" => $user->courses_count,
                    "full_name" => $user->name.' '.$user->surname,
                ];
            }),
            "levels" => ["Basico","Intermedio","Avanzado"],
            "idiomas" => ["Español","Ingles","Portuges"],
        ]);
    }

}
