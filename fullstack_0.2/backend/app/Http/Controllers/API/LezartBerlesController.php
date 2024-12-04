<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LezartBerlesResource;
use App\Models\LezartBerles;
use Illuminate\Http\Request;

class LezartBerlesController extends Controller
{
    public function index()
    {
         ### Minden lezárt bérlés lekérése az AUTOK adataival együtt.
         $lezartBerlesek = LezartBerles::with(['felhasznalo.szemely','auto'])->get();
        ## El tudd érni a LezartBerles-en belül/keresztül a Felhasznalokon [átmenve] a [szemely] adatokat.
        ##'szemely_knev' => $this->felhasznalo->szemely->k_nev,

        return LezartBerlesResource::collection($lezartBerlesek);
    }
    public function store(Request $request)
    {
        //
    }
    public function show(string $id)
    {
        //
    }
    public function update(Request $request, string $id)
    {
        //
    }
    public function destroy(int $id)
    {
        $egylezart = LezartBerles::with('auto')->findOrFail($id);
        $egylezart->delete();
        return response()->json(['A Bérlés törölve lett a rendszerből'], 200);
    }
}
