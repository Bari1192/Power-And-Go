<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSzemelyRequest;
use App\Http\Resources\SzemelyResource;
use App\Models\Szemely;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class SzemelyController extends Controller
{
    public function index(): JsonResource
    {
        $szemelyek = Szemely::all();
        return SzemelyResource::collection($szemelyek);
    }

    public function store(StoreSzemelyRequest $request)
    {
        $data = $request->validated();
        $szemely = Szemely::create($data);
        return new SzemelyResource($szemely);
    }

    public function show(Szemely $szemely)
    {
        return new SzemelyResource($szemely);
    }

    public function update(Request $request, Szemely $szemely)
    {
        //
    }

    public function destroy(Szemely $szemely):Response
    {
        return ($szemely->delete()) ? response()->noContent() : abort(500);
    }
}
