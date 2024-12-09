<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSzamlaRequest;
use App\Http\Resources\SzamlaResource;
use App\Http\Resources\ToltesBuntetesResource;
use App\Models\Szamla;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SzamlaController extends Controller
{
    public function index(Request $request): JsonResource
    {

        # 1. ötlet -> Frontenden fejlécre kattintás esetében:
        # index -> rendezés fejléc neve alapján (Pl: id, rendszám, stb.)

        $szamlak = Szamla::paginate(20);
        # 2. ötlet -> kezelnü a paginate (lapozást)
        return SzamlaResource::collection($szamlak);
    }
    public function store(StoreSzamlaRequest $request)
    {
        # 1. Adatellenőrzés
        $data = $request->validated();

        # 2. Szamla létrehozása
        $szamla = Szamla::create($data);

        # 3. Számla "visszaadása"
        return new SzamlaResource($szamla);
    }
    public function show(string $id)
    {
        //
    }
    public function update(Request $request, string $id)
    {
        //
    }
    public function destroy(string $id)
    {
        //
    }
    public function filter(string $type): JsonResource
    {
        $validFilterezes = ['buntetesek', 'baleset', 'karokozas', 'toltes_buntetes'];

        if (!in_array($type, $validFilterezes)) {
            return response()->json(['error' => 'Invalid filter type'], 400);
        }
        $szamlak = Szamla::where('szamla_tipus', '=', $type)->get();

        if ($type === 'toltes_buntetes') {
            $szamlak = Szamla::select('szamla_id', 'szamla_tipus', 'szemely_id', 'osszeg', 'szamla_kelt', 'felh_id', 'szamla_status')
                ->where('szamla_tipus', '=', $type)
                ->get();
            return ToltesBuntetesResource::collection($szamlak);
        } else {
            return SzamlaResource::collection($szamlak);
        }
    }
}
