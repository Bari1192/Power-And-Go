<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\BillResource;
use App\Http\Resources\CarResource;
use App\Models\Bill;
use App\Models\Car;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class CarController extends Controller
{
    public function index(): JsonResource
    {
        $cars = Car::all();
        return CarResource::collection($cars);
    }

    public function store(StoreCarRequest $request)
    {
        $data = $request->validated();
        $car = Car::create($data);
        return new CarResource($car);
    }

    public function show(Car $car): JsonResource
    { // Ekkor a Laravel automatikusan kikeresi az `id` alapján a rekordot
        ### Esetben az AutoResource automatikusan tartalmazni fogja a kapcsolódó flotta adatokat is
        ### (ha a Modelben megírtad!) + AutoResource megfelelően kezeli a relációkat.
        ### Resourcba ==>  'flotta' => new FlottaResource($this->whenLoaded('flotta')),

        $car->load(['flotta', 'carstatus', 'lezartberlesek']);
        return new CarResource($car);
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        $data = $request->validated();
        $car->load(['flotta', 'carstatus', 'lezartberlesek']);
        $car->update($data);
        return new CarResource($car);
    }
    public function destroy(Car $car): Response
    {
        return ($car->delete()) ? response()->noContent() : abort(500);
    }


    public function filterCarFines(Car $car): JsonResource
    {
        $szamlak = Bill::where('auto_azon', $car->autok_id)
            ->where('szamla_tipus', 'toltes_buntetes')
            ->get();

        return BillResource::collection($szamlak);
    }
}
