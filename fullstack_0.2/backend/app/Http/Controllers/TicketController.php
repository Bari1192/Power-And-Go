<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\CarStatus;
use App\Models\Ticket;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketController extends Controller
{
    public function index(): JsonResource
    {
        $tickets = Ticket::with('status')->get();
        return TicketResource::collection($tickets);
    }

    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();
        $data['bejelentve'] = now();

        $ticket = Ticket::create($data);

        return new TicketResource($ticket->load('status'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('status');
        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $data = $request->validated();
        $data['bejelentve'] = now();
        $ticket->load('status');
        $ticket->update($data);

        return new TicketResource($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        return ($ticket->delete() ? response(200) : abort(500));
    }
}
