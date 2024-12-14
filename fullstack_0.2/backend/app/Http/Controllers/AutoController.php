<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAutoRequest;
use App\Http\Resources\AutoResource;
use App\Http\Resources\SzamlaResource;
use App\Models\Auto;
use App\Models\Szamla;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class AutoController extends Controller
{
    public function index(): JsonResource
    {// $cars = Auto::with(['flotta','carstatus','lezartberlesek'])->get();

        $cars = Auto::with('flotta')->paginate(100,['*'],'oldal',1,);
        return AutoResource::collection($cars);
    }

    public function store(StoreAutoRequest $request)
    {
        $data = $request->validated();
        $car = Auto::create($data);
        return new AutoResource($car);
    }

    public function show(Auto $car): JsonResource
    { // Ekkor a Laravel automatikusan kikeresi az `id` alapján a rekordot
        ### Esetben az AutoResource automatikusan tartalmazni fogja a kapcsolódó flotta adatokat is
        ### (ha a Modelben megírtad!) + AutoResource megfelelően kezeli a relációkat.
        ### Resourcba ==>  'flotta' => new FlottaResource($this->whenLoaded('flotta')),

        $car->load(['flotta', 'carstatus', 'lezartberlesek']);
        return new AutoResource($car);
    }

    public function update(Request $request, string $id)
    {
        //
    }
    public function destroy(Auto $car): Response
    {
        return ($car->delete()) ? response()->noContent() : abort(500);
    }


    public function filterCarFines(Auto $car): JsonResource
    {
        $szamlak = Szamla::where('auto_azon', $car->autok_id)
        ->where('szamla_tipus','toltes_buntetes')
        ->get();

        return SzamlaResource::collection($szamlak);
    }
}
