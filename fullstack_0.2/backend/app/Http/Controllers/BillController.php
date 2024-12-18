<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use App\Http\Resources\BillResource;
use App\Http\Resources\ToltesBuntetesResource;
use App\Models\Bill;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class BillController extends Controller
{
    public function index(): JsonResource
    {
        $szamlak = Bill::orderBy('berles_veg_datum', 'desc')->paginate(20);
        return new BillResource($szamlak);
    }
    public function store(StoreBillRequest $request)
    {
        $data = $request->validated();
        $szamla = Bill::create($data);
        return new BillResource($szamla);
    }
    public function show(Bill $bill): JsonResource
    {
        $bill->load('autok');
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

    public function filter(string $type): JsonResource
    {
        $validFilterezes = ['berles', 'buntetesek', 'baleset', 'karokozas', 'toltes_buntetes'];

        if (!in_array($type, $validFilterezes)) {
            return response()->json(['error' => 'Invalid filter type'], 400);
        }
        $szamlak = Bill::where('szamla_tipus', '=', $type)->get();

        if ($type === 'toltes_buntetes') {
            $szamlak = Bill::select('szamla_id', 'szamla_tipus', 'szemely_id', 'osszeg', 'szamla_kelt', 'felh_id', 'szamla_status')
                ->where('szamla_tipus', '=', $type)
                ->get();
            return ToltesBuntetesResource::collection($szamlak);
        } else {
            return BillResource::collection($szamlak);
        }
    }
}
