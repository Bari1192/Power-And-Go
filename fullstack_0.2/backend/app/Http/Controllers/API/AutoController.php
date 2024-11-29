<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AutoResource;
use App\Models\Auto;
use Illuminate\Http\Request;

class AutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AutoResource::collection(Auto::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
        $auto=Auto::findOrFail($id);
        $auto->delete();
        return response()->json(['message'=>'Az kijelölt autót töröltük.'],200); ## 200 - OK -ot vissza!
    }
}
