<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAutoRequest;
use App\Http\Resources\AutoResource;
use App\Models\Auto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutoController extends Controller
{
    public function index(): JsonResource
    {
        $autok = Auto::all();
        return AutoResource::collection($autok);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAutoRequest $request)
    {
        $data = $request->validated();
        $car = Auto::create($data);
        return new AutoResource($car);
    }

    public function show(Auto $auto): JsonResource
    {
        return new AutoResource($auto);
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
