<?php

namespace App\Http\Controllers\Tienda;

use App\Models\Sale\Cart;
use Illuminate\Http\Request;
use App\Models\Coupon\Coupon;
use App\Models\CoursesStudent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Ecommerce\Cart\CartResource;
use App\Http\Resources\Ecommerce\Cart\CartCollection;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $user = auth('api')->user();

    if ($user) {
        $carts = Cart::where("user_id", $user->id)->get();
        return response()->json(["carts" => CartCollection::make($carts)]);
    } else {
        // Manejar el caso cuando $user es nulo
        // Por ejemplo, puedes devolver una respuesta JSON indicando que el usuario no está autenticado
        return response()->json(["message" => "Usuario no autenticado"], 401);
    }
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
        $user = auth('api')->user();
        // SI EL CURSO YA PERTECE AL STUDENTS
        $is_have_course = CoursesStudent::where("user_id",$user->id)->where("course_id",$request->course_id)->first();
        // SI EL CURSO YA EXISTE EN EL CARRITO
        $is_exits_cart = Cart::where("user_id",$user->id)->where("course_id",$request->course_id)->first();
        if($is_exits_cart){
            return response()->json(["message" => 403, "message_text" => "EL CURSO YA EXISTE EN LA LISTA"]);
        }
        if($is_have_course){
            return response()->json(["message" => 403, "message_text" => "YA HAS ADQUIRIDO ESTE CURSO"]);
        }

        $request->request->add(["user_id" => $user->id]);
        $cart = Cart::create($request->all());
        return response()->json(["cart" => CartResource::make($cart)]);
    }

    public function apply_coupon(Request $request)
    {
        $cupon = Coupon::where("code",$request->code)->where("state",1)->first();

        if(!$cupon){
            return response()->json(["message" => 403, "message_text" => "EL CUPON INGRESADO NO EXISTE"]);
        }

        $carts = Cart::where("user_id",auth('api')->user()->id)->get();

        foreach ($carts as $key => $cart) {
           if($cupon->type_coupon == 1){ // cupon es de tipo course
                $is_exits_course_cupon = false;
                foreach ($cupon->courses as $key => $course) {
                    if($course->course_id == $cart->course_id){
                        $is_exits_course_cupon = true;
                        break;
                    }
                }
                if($is_exits_course_cupon){
                    $total = 0;
                    if($cupon->type_discount == 1){//%
                        $total = $cart->precio_unitario - $cart->precio_unitario*($cupon->discount*0.01);
                    }
                    if($cupon->type_discount == 2){//USD
                        $total = $cart->precio_unitario - $cupon->discount;
                    }

                    $cart->update([
                        "type_discount" => $cupon->type_discount,
                        "discount" => $cupon->discount,
                        "type_campaing" => NULL,
                        "code_cupon" => $cupon->code,
                        "code_discount" => NULL,
                        "total" => $total,
                    ]);
                }
           }
           if($cupon->type_coupon == 2){ // cupon de tipo categoria
            $is_exits_course_cupon = false;
                foreach ($cupon->categories as $key => $categorie) {
                    if($categorie->categorie_id == $cart->course->categorie_id){
                        $is_exits_course_cupon = true;
                        break;
                    }
                }
                if($is_exits_course_cupon){
                    $total = 0;
                    if($cupon->type_discount == 1){//%
                        $total = $cart->precio_unitario - $cart->precio_unitario*($cupon->discount*0.01);
                    }
                    if($cupon->type_discount == 2){//USD
                        $total = $cart->precio_unitario - $cupon->discount;
                    }

                    $cart->update([
                        "type_discount" => $cupon->type_discount,
                        "discount" => $cupon->discount,
                        "type_campaing" => NULL,
                        "code_cupon" => $cupon->code,
                        "code_discount" => NULL,
                        "total" => $total,
                    ]);
                }
           }
        }

        $carts = Cart::where("user_id",auth('api')->user()->id)->get();

        return response()->json(["carts" => CartCollection::make($carts)]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();

        return response()->json(["message" => 200]);
    }
}
