<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRenthistoryRequest;
use App\Http\Requests\UpdateRenthistoryRequest;
use App\Http\Resources\RenthistoryResource;
use App\Http\Resources\SzamlaResource;
use App\Http\Resources\ToltesBuntetesResource;
use App\Models\Car;
use App\Models\Renthistory;
use App\Models\Szamla;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class RenthistoryController extends Controller
{
    public function index(): JsonResource
    {
        $histories = Renthistory::all();
        return RenthistoryResource::collection($histories);
    }

    public function store(StoreRenthistoryRequest $request)
    {
        $data=$request->validated();
        $renthistory=Renthistory::create($data);
        return new RenthistoryResource($renthistory);
    }

    public function show(Renthistory $renthistory): JsonResource
    {
        ### Csak a számlát tudod így lekérni!
        $renthistory->load("auto.tickets", "auto.carstatus", "kategoriak", "felhasznalo.szemely");
        return new RenthistoryResource($renthistory);
    }

    public function update(UpdateRenthistoryRequest $request, Renthistory $renthistory)
    {
        $data=$request->validated();
        $renthistory->load("auto.tickets", "auto.carstatus", "kategoriak", "felhasznalo.szemely");
        $renthistory->update($data);
        return new RenthistoryResource($renthistory);
    }

    public function destroy(Renthistory $renthistory):Response
    {
        return $renthistory->delete() ? response()->noContent() : abort(500);
    }

    public function filterCarHistory(string $type,Car $car): JsonResource
    {
        $validFilterezes = ['berles', 'buntetesek', 'baleset', 'karokozas', 'toltes_buntetes'];

        if (!in_array($type, $validFilterezes)) {
            return response()->json(['error' => 'Invalid filter type'], 400);
        }

        // Számlák lekérdezése típus és autó alapján
        $szamlak = Szamla::with('autok') // Kapcsolat betöltése
            ->where('szamla_tipus', $type)
            ->where('auto_azon', $car) // Auto azonosító szűrése
            ->get();

        // Ha 'toltes_buntetes' típus, akkor speciális resource
        if ($type === 'toltes_buntetes') {
            return ToltesBuntetesResource::collection($szamlak);
        }

        return SzamlaResource::collection($szamlak);
    }
}
