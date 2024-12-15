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
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $tickets = Ticket::all();
        return TicketResource::collection($tickets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        ### Amikor létrehozunk egy új ticket-et --> majd ELFOGADÁSI mechanizmus után 
        ### Menne a --> Bírságokhoz. 
        ### Egyelőre csak létrehozzuk.

        $data = $request->validated();
        $ticket = Ticket::create($data);
        return new TicketResource($ticket);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
