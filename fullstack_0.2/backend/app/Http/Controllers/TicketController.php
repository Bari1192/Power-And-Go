<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketController extends Controller
{
    public function index(): JsonResource
    {
        $tickets = Ticket::all();
        return TicketResource::collection($tickets);
    }

    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();
        $data['szamla_kelt'] = now(); ## Expliciten belevágjuk
        $ticket = Ticket::create($data);
        return new TicketResource($ticket);
    }

    public function show(Ticket $ticket)
    {
        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $data = $request->validated();
        $data['created'] = now(); ## Expliciten belevágjuk frissítéskor is átírva!
        $ticket->update($data);
        return new TicketResource($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        return ($ticket->delete() ? response(200) : abort(500));
    }
}
