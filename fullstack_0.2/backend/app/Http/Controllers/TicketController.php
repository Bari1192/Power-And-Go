<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketController extends Controller
{

    #####   ####   ####   ####   ####   ####
    public function index(): JsonResource
    {
        $tickets = Ticket::all();
        return TicketResource::collection($tickets);
    }

    #####   ####   ####   ####   ####   ####
    public function store(StoreTicketRequest $request)
    {
        ### Amikor létrehozunk egy új ticket-et --> majd ELFOGADÁSI mechanizmus után 
        ### Menne a --> Bírságokhoz. 
        ### Egyelőre csak létrehozzuk.

        $data = $request->validated();
        $ticket = Ticket::create($data);
        return new TicketResource($ticket);
    }

    #####   ####   ####   ####   ####   ####
    public function show(Ticket $ticket)
    {
        return new TicketResource($ticket);
    }

    #####   ####   ####   ####   ####   ####
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $data = $request->validated();
        $ticket->update($data);
        return new TicketResource($ticket); // Frissített adat visszaadása
    }
    #####   ####   ####   ####   ####   ####
    public function destroy(Ticket $ticket)
    {
        return ($ticket->delete() ? response(200) : abort(500));
    }
}
