<?php

namespace App\Http\Controllers\Admin\Discount;

use Illuminate\Http\Request;
use App\Models\Discount\Discount;
use App\Http\Controllers\Controller;
use App\Models\Discount\DiscountCourse;
use App\Models\Discount\DiscountCategorie;
use App\Http\Resources\Discount\DiscountResource;
use App\Http\Resources\Discount\DiscountCollection;
    
class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $state = $request->state;
        // filterAdvance($search,$state)->
        $discounts = Discount::orderBy("id","desc")->get();

        return response()->json(["message" => 200, "discounts" => DiscountCollection::make($discounts)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //discount_type 1 es course y 2 es categoria

        if($request->discount_type == 1){//VALIDACIÓN A NIVEL DE CURSO
            foreach ($request->course_selected as $key => $course) {
                $IS_DISCOUNT_START_DATE = Discount::where("type_campaing",$request->type_campaing)->where("discount_type",$request->discount_type)->whereHas("courses",function($q) use($course){
                    return $q->where("course_id",$course["id"]);
                })->whereBetween("start_date",[$request->start_date,$request->end_date])->first();

                $IS_DISCOUNT_END_DATE = Discount::where("type_campaing",$request->type_campaing)->where("discount_type",$request->discount_type)->whereHas("courses",function($q) use($course){
                    return $q->where("course_id",$course["id"]);
                })->whereBetween("end_date",[$request->start_date,$request->end_date])->first();

                if($IS_DISCOUNT_START_DATE || $IS_DISCOUNT_END_DATE){
                    return response()->json(["message" => 403, "message_text" => "EL CURSO '".$course["title"]."' YA SE ENCUENTRA EN UNA CAMPAÑA DE DESCUENTO - ".($IS_DISCOUNT_START_DATE ? $IS_DISCOUNT_START_DATE->id : '-').($IS_DISCOUNT_END_DATE ? $IS_DISCOUNT_END_DATE->id : '')]);
                }
            }
        }

        if($request->discount_type == 2){//VALIDACIÓN A NIVEL DE CATEGORIA  
            foreach ($request->categorie_selected as $key => $categorie) {
                $IS_DISCOUNT_START_DATE = Discount::where("type_campaing",$request->type_campaing)->where("discount_type",$request->discount_type)->whereHas("categories",function($q) use($categorie){
                    return $q->where("category_id",$categorie["id"]);
                })->whereBetween("start_date",[$request->start_date,$request->end_date])->first();

                $IS_DISCOUNT_END_DATE = Discount::where("type_campaing",$request->type_campaing)->where("discount_type",$request->discount_type)->whereHas("categories",function($q) use($categorie){
                    return $q->where("category_id",$categorie["id"]);
                })->whereBetween("end_date",[$request->start_date,$request->end_date])->first();

                if($IS_DISCOUNT_START_DATE || $IS_DISCOUNT_END_DATE){
                    return response()->json(["message" => 403, "message_text" => "LA CATEGORIA '".$categorie["name"]."' YA SE ENCUENTRA EN UNA CAMPAÑA DE DESCUENTO - ".($IS_DISCOUNT_START_DATE ? $IS_DISCOUNT_START_DATE->id : '-').($IS_DISCOUNT_END_DATE ? $IS_DISCOUNT_END_DATE->id : '')]);
                }
            }
        }

        $request->request->add(["code" => uniqid()]);
        $discount = Discount::create($request->all());

        if($request->discount_type == 1){// 1 es course
            foreach ($request->course_selected as $key => $course) {
               DiscountCourse::create([
                "discount_id" => $discount->id,
                "course_id" => $course["id"],
               ]);
            }
        }
        if($request->discount_type == 2){// 2 es categoria
            foreach ($request->categorie_selected as $key => $categorie) {
                DiscountCategorie::create([
                    "discount_id" => $discount->id,
                    "category_id" => $categorie["id"],
                ]);
            }
        }

        return response()->json(["message" => 200 ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discount = Discount::findOrFail($id);

        return response()->json(["discount" =>DiscountResource::make($discount)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->discount_type == 1){//VALIDACIÓN A NIVEL DE CURSO
            foreach ($request->course_selected as $key => $course) {
                $IS_DISCOUNT_START_DATE = Discount::where("id","<>",$id)->where("type_campaing",$request->type_campaing)->where("discount_type",$request->discount_type)->whereHas("courses",function($q) use($course){
                    return $q->where("course_id",$course["id"]);
                })->whereBetween("start_date",[$request->start_date,$request->end_date])->first();

                $IS_DISCOUNT_END_DATE = Discount::where("id","<>",$id)->where("type_campaing",$request->type_campaing)->where("discount_type",$request->discount_type)->whereHas("courses",function($q) use($course){
                    return $q->where("course_id",$course["id"]);
                })->whereBetween("end_date",[$request->start_date,$request->end_date])->first();

                if($IS_DISCOUNT_START_DATE || $IS_DISCOUNT_END_DATE){
                    return response()->json(["message" => 403, "message_text" => "EL CURSO '".$course["title"]."' YA SE ENCUENTRA EN UNA CAMPAÑA DE DESCUENTO - ".($IS_DISCOUNT_START_DATE ? $IS_DISCOUNT_START_DATE->id : '-').($IS_DISCOUNT_END_DATE ? $IS_DISCOUNT_END_DATE->id : '')]);
                }
            }
        }

        if($request->discount_type == 2){//VALIDACIÓN A NIVEL DE CATEGORIA  
            foreach ($request->categorie_selected as $key => $categorie) {
                $IS_DISCOUNT_START_DATE = Discount::where("id","<>",$id)->where("type_campaing",$request->type_campaing)->where("discount_type",$request->discount_type)->whereHas("categories",function($q) use($categorie){
                    return $q->where("category_id",$categorie["id"]);
                })->whereBetween("start_date",[$request->start_date,$request->end_date])->first();

                $IS_DISCOUNT_END_DATE = Discount::where("id","<>",$id)->where("type_campaing",$request->type_campaing)->where("discount_type",$request->discount_type)->whereHas("categories",function($q) use($categorie){
                    return $q->where("category_id",$categorie["id"]);
                })->whereBetween("end_date",[$request->start_date,$request->end_date])->first();

                if($IS_DISCOUNT_START_DATE || $IS_DISCOUNT_END_DATE){
                    return response()->json(["message" => 403, "message_text" => "LA CATEGORIA '".$categorie["name"]."' YA SE ENCUENTRA EN UNA CAMPAÑA DE DESCUENTO - ".($IS_DISCOUNT_START_DATE ? $IS_DISCOUNT_START_DATE->id : '-').($IS_DISCOUNT_END_DATE ? $IS_DISCOUNT_END_DATE->id : '')]);
                }
            }
        }

        $request->request->add(["code" => uniqid()]);
        $discount = Discount::findOrFail($id);
        $discount->update($request->all());


        foreach ($discount->courses as $key => $courseD) {
            $courseD->delete();
        }
        foreach ($discount->categories as $key => $categorieD) {
            $categorieD->delete();
        }

        if($request->discount_type == 1){// 1 es course
            foreach ($request->course_selected as $key => $course) {
               DiscountCourse::create([
                "discount_id" => $discount->id,
                "course_id" => $course["id"],
               ]);
            }
        }
        if($request->discount_type == 2){// 2 es categoria
            foreach ($request->categorie_selected as $key => $categorie) {
                DiscountCategorie::create([
                    "discount_id" => $discount->id,
                    "category_id" => $categorie["id"],
                ]);
            }
        }

        return response()->json(["message" => 200 ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

        return response()->json(["message" => 200]);
    }
}