<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRenthistoryRequest;
use App\Http\Requests\UpdateRenthistoryRequest;
use App\Http\Resources\RenthistoryResource;
use App\Http\Resources\SzamlaResource;
use App\Http\Resources\ToltesBuntetesResource;
use App\Models\Auto;
use App\Models\LezartBerles;
use App\Models\Szamla;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class RenthistoryController extends Controller
{
    public function index(): JsonResource
    {
        $histories = LezartBerles::with(["auto.tickets", "auto.carstatus", "kategoriak", "felhasznalo.szemely"])->get();
        return RenthistoryResource::collection($histories);
    }

    public function store(StoreRenthistoryRequest $request)
    {
        $data=$request->validated();
        $renthistory=LezartBerles::create($data);
        return new RenthistoryResource($renthistory);
    }

    public function show(LezartBerles $renthistory): JsonResource
    {
        ### Csak a számlát tudod így lekérni!
        $renthistory->load("auto.tickets", "auto.carstatus", "kategoriak", "felhasznalo.szemely");
        return new RenthistoryResource($renthistory);
    }

    public function update(UpdateRenthistoryRequest $request, LezartBerles $renthistory)
    {
        $data=$request->validated();
        $renthistory->load("auto.tickets", "auto.carstatus", "kategoriak", "felhasznalo.szemely");
        $renthistory->update($data);
        return new RenthistoryResource($renthistory);
    }

    public function destroy(LezartBerles $renthistory):Response
    {
        return $renthistory->delete() ? response()->noContent() : abort(500);
    }

    public function filterCarHistory(string $type,Auto $car): JsonResource
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
