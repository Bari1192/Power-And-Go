<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoogleMapsController extends Controller
{
    public function getApiUrl(){
        $apiKulcs=env('VITE_GOOGLE_MAPS_API_KEY');
        $url = "https://maps.googleapis.com/maps/api/js?key={$apiKulcs}&libraries=places";

        return response()->json(['url'=>$url]);
    }
}
