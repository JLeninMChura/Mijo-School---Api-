<?php

namespace App\Http\Controllers\Admin\Course;

use App\Http\Controllers\Controller;
use App\Models\Course\CourseSection;
use Illuminate\Http\Request;

class SeccionGController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sections = CourseSection::where("course_id", $request->course_id)->orderBy("id", "asc")->get();
        return response()->json([
            "sections" => $sections
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
        $sections = CourseSection::create($request->all());
        return response()->json([
            "sections" => $sections
        ]);
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
        $sections = CourseSection::findOrFail($id);
        $sections->update($request->all());
        return response()->json([
            "sections" => $sections
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sections = CourseSection::findOrFail($id);
        if ($sections->clases->count() > 0) {
            return response()->json([
                "message" => 403,
                "message_text" => "Sección con clases dentro, No se puede eliminar",
            ]);
        }
        $sections->delete();
        return response()->json([
            "message" => 200
        ]);
    }
}
