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
        $bills = Bill::with(['users', 'cars.users', 'persons'])->get();
        return BillResource::collection($bills);
    }
    public function store(StoreBillRequest $request)
    {
        $data = $request->validated();
        $bill = Bill::create($data);

        return new BillResource($bill);
    }
    public function show(Bill $bill): JsonResource
    {
        $bill->load(['cars', 'users', 'persons']);
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
  
    public function closedRents(): JsonResource
    {
        $bills = Bill::with(['users', 'cars.users', 'persons'])
            ->where('bill_type', 'rental')
            ->get();
        return BillResource::collection($bills);
    }
    public function feesCollection(): JsonResource
    {
        $bills = Bill::with(['users', 'cars.users', 'persons'])
            ->where('bill_type', 'charging_penalty')
            ->get();
        return BillResource::collection($bills);
    }
}
