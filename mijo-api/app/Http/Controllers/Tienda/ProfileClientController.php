<?php

namespace App\Http\Controllers\Tienda;

use App\Models\User;
use App\Models\Sale\Sale;
use Illuminate\Http\Request;
use App\Models\CoursesStudent;
use App\Models\Sale\SaleDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Ecommerce\Sale\SaleCollection;
use App\Http\Resources\Ecommerce\Course\CourseHomeResource;

class ProfileClientController extends Controller
{
    public function profile(Request $request)
    {
        try {
            $user = auth('api')->user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $enrolled_course_count = CoursesStudent::where("user_id", $user->id)->count();
            $active_course_count = CoursesStudent::where("user_id", $user->id)->whereNotNull("clases_checkeds")->count();
            $termined_course_count = CoursesStudent::where("user_id", $user->id)->where("state", 2)->count();

            $enrolled_courses = CoursesStudent::where("user_id", $user->id)->get();
            $active_courses = CoursesStudent::where("user_id", $user->id)->whereNotNull("clases_checkeds")->get();
            $termined_courses = CoursesStudent::where("user_id", $user->id)->where("state", 2)->get();

            $sale_details = SaleDetail::whereHas("sale", function($q) use($user) {
                $q->where("user_id", $user->id);
            })->orderBy("id", "desc")->get();

            $sales = Sale::where("user_id", $user->id)->orderBy("id", "desc")->get();

            return response()->json([
                "user" => $this->formatUserData($user),
                "enrolled_course_count" => $enrolled_course_count,
                "active_course_count" => $active_course_count,
                "termined_course_count" => $termined_course_count,
                "enrolled_courses" => $this->formatCourses($enrolled_courses),
                "active_courses" => $this->formatCourses($active_courses),
                "termined_courses" => $this->formatCourses($termined_courses),
                "sale_details" => $this->formatSaleDetails($sale_details),
                "sales" => SaleCollection::make($sales),
            ]);
        } catch (\Exception $e) {
            Log::error('Error in profile method: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function update_client(Request $request)
    {
        try {
            $user = User::findOrFail(auth("api")->user()->id);
            
            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',
                'surname' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,'.$user->id,
                'phone' => 'sometimes|string|max:20',
                'profesion' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'new_password' => 'sometimes|string|min:6',
                'imagen' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if(isset($validatedData['new_password'])){
                $validatedData['password'] = Hash::make($validatedData['new_password']);
                unset($validatedData['new_password']);
            }
            
            if($request->hasFile("imagen")){
                if($user->avatar){
                    Storage::delete($user->avatar);
                }
                $path = Storage::putFile("users", $request->file("imagen"));
                $validatedData['avatar'] = $path;
            }
            
            $user->update($validatedData);

            return response()->json(["message" => "Profile updated successfully", "status" => 200]);
        } catch (\Exception $e) {
            Log::error('Error in update_client method: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    private function formatUserData($user)
    {
        return [
            "name" => $user->name,
            "surname" => $user->surname ?? '',
            "email" => $user->email,
            "phone" => $user->phone ?? '',
            "profesion" => $user->profesion ?? '',
            "description" => $user->description ?? '',
            "avatar" => $user->avatar ? env("APP_URL") . "storage/" . $user->avatar : null,
        ];
    }

    private function formatCourses($courses)
    {
        return $courses->map(function($course_student) {
            $clases_checkeds = $course_student->clases_checkeds ? explode(",", $course_student->clases_checkeds) : [];
            return [
                "id" => $course_student->id,
                "clases_checkeds" => $clases_checkeds,
                "percentage" => $this->calculatePercentage($clases_checkeds, $course_student->course),
                "course" => CourseHomeResource::make($course_student->course),
            ];
        });
    }

    private function formatSaleDetails($sale_details)
    {
        return $sale_details->map(function($sale_detail) {
            return [
                "id" => $sale_detail->id,
                "review" => $sale_detail->review,
                "course" => [
                    "id" => $sale_detail->course->id,
                    "title" => $sale_detail->course->title,
                    "imagen" => env("APP_URL") . "storage/" . $sale_detail->course->imagen,
                ],
                "created_at" => $sale_detail->created_at->format("Y-m-d h:i:s"),
            ];
        });
    }

    private function calculatePercentage($clases_checkeds, $course)
    {
        $count_class = $course->count_class ?? 0;
        if ($count_class == 0) {
            return 0; // Evita la división por cero
        }
        return round((count($clases_checkeds) / $count_class) * 100, 2);
    }
}