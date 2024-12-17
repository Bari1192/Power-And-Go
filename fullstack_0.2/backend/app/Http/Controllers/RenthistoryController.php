<?php

namespace App\Http\Controllers;

use App\Http\Resources\RenthistoryResource;
use App\Http\Resources\SzamlaResource;
use App\Http\Resources\ToltesBuntetesResource;
use App\Models\Auto;
use App\Models\LezartBerles;
use App\Models\Szamla;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RenthistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $histories = LezartBerles::with(["auto.tickets", "auto.carstatus", "kategoriak", "felhasznalo.szemely"])->get();
        return RenthistoryResource::collection($histories);
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LezartBerles $renthistory): JsonResource
    {
        ### Csak a számlát tudod így lekérni!
        $renthistory->load("auto.tickets", "auto.carstatus", "kategoriak", "felhasznalo.szemely");
        return new RenthistoryResource($renthistory);
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

    public function filterCarHistory(string $type, $car): JsonResource
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
