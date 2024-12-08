<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSzamlaRequest;
use App\Http\Resources\SzamlaResource;
use App\Models\Szamla;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SzamlaController extends Controller
{
    public function index(): JsonResource
    {
        $szamlak = Szamla::all();
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
}
