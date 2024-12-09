<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFleetRequest;
use App\Http\Resources\FleetResource;
use App\Models\Flotta_tipusok;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

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

    public function show(Flotta_tipusok $flotta_tipusok)
    {
        return new FleetResource($flotta_tipusok);
    }
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(Flotta_tipusok $flotta_tipusok): Response
    {
        return ($flotta_tipusok->delete()) ? response()->noContent() : abort(500);
    }
}
