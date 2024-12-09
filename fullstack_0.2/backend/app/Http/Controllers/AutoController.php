<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAutoRequest;
use App\Http\Resources\AutoResource;
use App\Models\Auto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class AutoController extends Controller
{
    public function index(): JsonResource
    {
        $autok = Auto::all();
        return AutoResource::collection($autok);
    }

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

    public function update(Request $request, string $id)
    {
        //
    }
    public function destroy(Auto $auto): Response
    {
        return ($auto->delete()) ? response()->noContent() : abort(500);
    }
}
