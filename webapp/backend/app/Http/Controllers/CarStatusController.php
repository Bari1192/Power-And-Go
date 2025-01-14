<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarStatusRequest;
use App\Http\Requests\UpdateCarstatusRequest;
use App\Http\Resources\CarstatusResource;
use App\Models\CarStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class CarStatusController extends Controller
{
    public function index(): JsonResource
    {
        $statuses = CarStatus::all();
        return CarstatusResource::collection($statuses);
    }

    public function store(StoreCarStatusRequest $request)
    {
        $data = $request->validated();
        $carstatus = CarStatus::create($data);
        return new CarstatusResource($carstatus);
    }
    public function show(CarStatus $carstatus):JsonResource
    {
        return new CarstatusResource($carstatus);
    }

    public function update(UpdateCarstatusRequest $request, CarStatus $carstatus)
    {
        $data=$request->validated();
        $carstatus->update($data);
        return new CarstatusResource($carstatus);
    }
    
    public function destroy(CarStatus $carstatus):Response
    {
        return($carstatus->delete()) ? response()->noContent() : abort(500);
    }

}
