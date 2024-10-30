<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Mail\SaleMail;
use App\Models\CoursesStudent;
use App\Models\Sale\Cart;

use App\Models\Sale\Sale;
use App\Models\Sale\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->request->add(["user_id" => auth("api")->user()->id]);
        $sale = Sale::create($request->all());

        $carts = Cart::where("user_id",auth('api')->user()->id)->get();

        foreach ($carts as $key => $cart) {
            // $cart->delete();
            $new_detail = [];
            $new_detail = $cart->toArray();
            $new_detail["sale_id"] = $sale->id;
            SaleDetail::create($new_detail);
            CoursesStudent::create([
                "course_id" =>$new_detail["course_id"],
                "user_id" => auth('api')->user()->id,
            ]);
        }   

        // AQUI VA EL CODIGO PARA EL ENVIO DE CORREO
        Mail::to($sale->user->email)->send(new SaleMail($sale)); 
        return response()->json(["message" => 200, "message_text" => "LOS CURSOS SE HAN ADQUIRIDO CORRECTAMENTE"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
