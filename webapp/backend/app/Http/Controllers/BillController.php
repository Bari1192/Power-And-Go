<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use App\Http\Resources\BillResource;
use App\Mail\RentalSummaryEmail;
use App\Models\Bill;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BillController extends Controller
{
    public function index(): JsonResource
    {
        $bills = Bill::with(['cars', 'users', 'persons'])->get();
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

    public function carFees(Bill $bills, Car $car): JsonResource
    {
        $bills = Bill::where("car_id", $car->id)
            ->where('bill_type', 'charging_penalty')
            ->get()
            ->load(['persons', 'users']);
        return BillResource::collection($bills);
    }
    public function closedRents(): JsonResource
    {
        $bills = Bill::with(['cars', 'users', 'persons'])
            ->where('bill_type', 'rental')
            ->get();
        return BillResource::collection($bills);
    }

    public function sendRentSummaryEmail(Request $request)
    {
        try {
            // Validáljuk a bejövő adatokat
            $request->validate([
                'rentId' => 'required|exists:bills,id',
                'emailHtml' => 'required|string'
            ]);

            $rentId = $request->input('rentId');
            $emailHtml = $request->input('emailHtml');

            // Keressük meg a bérlést és a hozzá tartozó személyt
            $bill = Bill::with('persons')->find($rentId);

            if (!$bill || !$bill->persons || !$bill->persons->email) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nem található email cím ehhez a bérléshez'
                ], 400);
            }

            // Küldjük el az emailt
            try {
                Mail::html($emailHtml, function ($message) use ($bill) {
                    $message->to($bill->persons->email)
                        ->subject("PowerAndGo bérlés összefoglaló #{$bill->id}");
                });

                Log::info("Email sikeresen elküldve", [
                    'email' => $bill->persons->email,
                    'rent_id' => $bill->id
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Email sikeresen elküldve',
                    'data' => [
                        'email' => $bill->persons->email,
                        'rent_id' => $bill->id
                    ]
                ]);
            } catch (\Exception $e) {
                Log::error("Hiba az email küldésekor", [
                    'email' => $bill->persons->email,
                    'rent_id' => $bill->id,
                    'error' => $e->getMessage()
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Hiba az email küldésekor: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error("Kritikus hiba az email küldéskor", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Hiba az email küldés során: ' . $e->getMessage()
            ], 500);
        }
    }
}
