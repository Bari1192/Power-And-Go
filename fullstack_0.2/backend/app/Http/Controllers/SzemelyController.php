<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSzemelyRequest;
use App\Http\Resources\SzemelyResource;
use App\Models\Szemely;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SzemelyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $szemelyek = Szemely::all();
        return SzemelyResource::collection($szemelyek);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSzemelyRequest $request)
    {
        $data = $request->validated();
        $szemely = Szemely::create($data);
        return new SzemelyResource($szemely);
    }

    /**
     * Display the specified resource.
     */
    public function show(Szemely $szemely)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Szemely $szemely)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Szemely $szemely)
    {
        //
    }
}
