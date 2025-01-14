<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\BillResource;
use App\Http\Resources\CarResource;
use App\Http\Resources\CarWithUsersResource;
use App\Http\Resources\TicketResource;
use App\Models\Bill;
use App\Models\Car;
use App\Models\Ticket;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class CarController extends Controller
{
    public function index(): JsonResource
    {
        $cars = Car::with(['fleet', 'tickets'])->get();
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
        $car->load(['fleet']);
        return new CarResource($car);
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        $data = $request->validated();
        $car->load('fleet');
        $car->update($data);
        return new CarResource($car);
    }
    public function destroy(Car $car): Response
    {
        return ($car->delete()) ? response()->noContent() : abort(500);
    }
    public function filterCarFines(Car $car): JsonResource
    {
        $szamlak = Bill::with(['users', 'persons'])
        ->where('car_id', $car->id)
        ->where('szamla_tipus', 'toltes_buntetes')
        ->get();

        return BillResource::collection($szamlak);
    }
    public function carTickets(Car $car): JsonResource
    {
        $tickets = Ticket::with('status')
            ->where('car_id', $car->id)
            ->get();

        return TicketResource::collection($tickets);
    }
    public function carWithRentHistory(Car $car): JsonResource
    {
        $car = Car::with(['users', 'fleet', 'users.person'])->find($car->id);
        return new CarWithUsersResource($car);
    }

    ## Utolsó ticket lekérjük az autó ID alapján, státusz szöveggel.
    public function carLastTicketDescription(Car $car): JsonResource
    {
        $tickets = Ticket::with('status')
            ->where('car_id', $car->id)
            ->orderBy('id', 'desc')
            ->First();
        return new TicketResource($tickets);
    }
}
