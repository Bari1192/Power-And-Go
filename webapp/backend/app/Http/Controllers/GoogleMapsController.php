<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleMapsController extends Controller
{
    public function getApiUrl()
    {
        $apiKulcs = env('VITE_GOOGLE_MAPS_API_KEY');
        $mapId = env('GOOGLE_MAPS_MAP_ID');
        $url = "https://maps.googleapis.com/maps/api/js?key={$apiKulcs}&libraries=places,marker&map_ids={$mapId}";

        return response()->json(['url' => $url]);
    }


    public function getGeocode(Request $request)
    {
        $address = $request->query('address');
        $apiKulcs = env('VITE_GOOGLE_MAPS_API_KEY');
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'address' => $address,
            'key' => $apiKulcs,
        ]);

        return response()->json($response->json());
    }
}
