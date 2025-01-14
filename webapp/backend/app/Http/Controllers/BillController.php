<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use App\Http\Resources\BillResource;
use App\Models\Bill;
use App\Models\Car;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class BillController extends Controller
{
    public function index(): JsonResource
    {
        $bills = Bill::with("cars")->get();
        return BillResource::collection($bills);
    }
    public function store(StoreBillRequest $request)
    {
        $data = $request->validated();
        $szamla = Bill::create($data);
        return new BillResource($szamla);
    }
    public function show(Bill $bill): JsonResource
    {
        $bill->load('cars');
        return new BillResource($bill);
    }
    public function update(UpdateBillRequest $request, Bill $bill)
    {
        $data = $request->validated();
        $bill->update($data);
        return new BillResource($bill);
    }

    public function destroy(Bill $bill): Response
    {
        return ($bill->delete()) ? response()->noContent() : abort(500);
    }
    public function carFees(Bill $bills, Car $car): JsonResource
    {
        $bills = Bill::where("car_id", $car->id)
            ->where('szamla_tipus', 'toltes_buntetes')
            ->get();
        $bills->load(['persons','users']);
        return BillResource::collection($bills);
    }
}
