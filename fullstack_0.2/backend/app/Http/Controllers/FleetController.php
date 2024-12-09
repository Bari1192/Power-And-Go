<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFleetRequest;
use App\Http\Resources\FleetResource;
use App\Models\Flotta_tipusok;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FleetController extends Controller
{
    public function index(): JsonResource
    {
        $fleets = Flotta_tipusok::all();
        return FleetResource::collection($fleets);
    }

    public function store(StoreFleetRequest $request)
    {
        $data = $request->validated();
        $fleet = Flotta_tipusok::create($data);
        return new FleetResource($fleet);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
