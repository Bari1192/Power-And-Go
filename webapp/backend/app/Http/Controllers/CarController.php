<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\BillResource;
use App\Http\Resources\CarResource;
use App\Http\Resources\CarWithFinesResource;
use App\Http\Resources\CarWithUsersResource;
use App\Http\Resources\TicketResource;
use App\Models\Bill;
use App\Models\Car;
use App\Models\Ticket;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

use function PHPUnit\Framework\isEmpty;

class CarController extends Controller
{
    public function index(): JsonResource
    {
        $cars = Car::with(['fleet', 'tickets', 'carstatus'])->get();
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

    ###     Egyedi kérések     ###


    public function filterCarFees(Car $car): CarWithFinesResource
    {
        $car->load(['bills' => function ($query) {$query->where('bill_type', 'charging_penalty');},'bills.users.person']);
        return new CarWithFinesResource($car);
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
        $car = Car::with(['users', 'fleet', 'users.person', 'bills'])
            ->orderBy('id', 'desc')
            ->find($car->id);
        return new CarWithUsersResource($car);
    }
    ## Utolsó ticket lekérjük az autó ID alapján, státusz szöveggel.
    public function carLastTicketDescription(Car $car)
    {
        $ticket = Ticket::with('status')
            ->where('car_id', $car->id)
            ->firstOrFail();
        if (!empty($ticket)) {
            return new TicketResource($ticket);
        }
        return response()->json(["message" => 'Ticket Description not found.']);
    }
}
