<?php

namespace App\Http\Controllers;

use App\Http\Resources\RenthistoryResource;
use App\Models\LezartBerles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RenthistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $histories = LezartBerles::with(["auto.tickets","auto.carstatus","kategoriak", "felhasznalo.szemely"])->get();
        return RenthistoryResource::collection($histories);
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LezartBerles $lezartberles): JsonResource
    {
        return new RenthistoryResource($lezartberles);
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
}
