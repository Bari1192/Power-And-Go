<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarStatusRequest;
use App\Http\Resources\CarstatusResource;
use App\Models\CarStatus;
use Illuminate\Http\Request;
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
    public function show(CarStatus $carStatus)
    {
        return new CarstatusResource($carStatus);
    }

    public function update(Request $request, string $id)
    {
        
    }
    
    public function destroy(CarStatus $carStatus):Response
    {
        return($carStatus->delete()) ? response()->noContent() : abort(500);
    }

}
