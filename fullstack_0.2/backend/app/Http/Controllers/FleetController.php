<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFleetRequest;
use App\Http\Requests\UpdateFleetRequest;
use App\Http\Resources\FleetResource;
use App\Models\Fleet;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class FleetController extends Controller
{
    public function index(): JsonResource
    {
        $fleets = Fleet::all();
        return FleetResource::collection($fleets);
    }

    public function store(StoreFleetRequest $request)
    {
        $data = $request->validated();
        $fleet = Fleet::create($data);
        return new FleetResource($fleet);
    }

    public function show(Fleet $flotta_tipusok)
    {
        return new FleetResource($flotta_tipusok);
    }
    public function update(UpdateFleetRequest $request, Fleet $fleet)
    {
        $data=$request->validated();
        $fleet->update($data);
        return new FleetResource($fleet);
    }

    public function destroy(Fleet $fleet)
    {
        if ($fleet->delete()) {
            return response(204);
        }
        return abort(500,'A törlés sikertelen volt!');
    }
}
