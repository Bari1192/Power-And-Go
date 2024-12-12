<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAutoRequest;
use App\Http\Resources\AutoResource;
use App\Models\Auto;
use App\Models\Flotta_tipusok;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class AutoController extends Controller
{
    public function index(): JsonResource
    {
        $cars = Auto::all();
        return AutoResource::collection($cars);
    }

    public function store(StoreAutoRequest $request)
    {
        $data = $request->validated();
        $car = Auto::create($data);
        return new AutoResource($car);
    }

    public function show(Auto $car): JsonResource
    {// Ekkor a Laravel automatikusan kikeresi az `id` alapján a rekordot
        
        ### Esetben az AutoResource automatikusan tartalmazni fogja a kapcsolódó flotta adatokat is
        ### (ha a Modelben megírtad!) + AutoResource megfelelően kezeli a relációkat.
        ### Resourcba ==>  'flotta' => new FlottaResource($this->whenLoaded('flotta')),

        $car->load('flotta');
        
        return new AutoResource($car); 
    }

    public function update(Request $request, string $id)
    {
        //
    }
    public function destroy(Auto $car): Response
    {
        return ($car->delete()) ? response()->noContent() : abort(500);
    }
}
