<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\BillResource;
use App\Http\Resources\CarResource;
use App\Http\Resources\CarWithUsersResource;
use App\Models\Bill;
use App\Models\Car;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class CarController extends Controller
{
    public function index(): JsonResource
    {
        $cars = Car::with('fleet')->get();
        return CarResource::collection($cars);
    }

    public function store(StoreCarRequest $request)
    {
        $data = $request->validated();
        $car = Car::create($data);
        return new CarResource($car);
    }

    public function show(Car $car): JsonResource
    {
        $car->load(['users','fleet','users.person']);
        return new CarWithUsersResource($car);
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        $data = $request->validated();
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
