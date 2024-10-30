<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserGCollection;
use App\Http\Resources\User\UserGResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $state  = $request->state;
        $type_user  = $request->type_user;
        $users = User::filterAdvance($search, $state, $type_user)->orderby("id", "desc")->get();

        return response()->json([
            "users" => UserGCollection::make($users)
        ]);
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
        if ($request->hasFile("imagen")) {
            $path = Storage::putFile("users",$request->file("imagen"));
            $request->request->add(["avatar"=>$path]);
        }
        if ($request->password) {
            $request->request->add(["password"=>bcrypt($request->password)]);
        }
        $user=User::create($request->all());

        return response()->json(["user" => UserGResource::make($user)]);
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
        $user=User::findOrFail($id);
        if ($request->hasFile("imagen")) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $path = Storage::putFile("users",$request->file("imagen"));
            $request->request->add(["avatar"=>$path]);
        }
        if ($request->password) {
            $request->request->add(["password"=>bcrypt($request->password)]);
        }     
        $user->update($request->all());
        return response()->json(["user" => UserGResource::make($user)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(["message"=>200]);
    }
}