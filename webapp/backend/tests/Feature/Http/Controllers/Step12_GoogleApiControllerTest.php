<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class Step12_GoogleApiControllerTest extends TestCase
{
    public function test_can_get_google_api_url_with_api_key_and_map_id()
    {
        $apiKulcs = env('VITE_GOOGLE_MAPS_API_KEY');
        $mapId = env('GOOGLE_MAPS_MAP_ID');
        $baseURL = env('APP_URL');
        $url = "{$baseURL}/api/googlemapsapi";
        $assertURL = "https://maps.googleapis.com/maps/api/js?key={$apiKulcs}&libraries=places,marker&map_ids={$mapId}";

        $response = $this->get($url);
        $response->assertStatus(200);

        $this->assertArrayHasKey('url', $response->json());
        $response->assertJsonFragment([
            'url' => $assertURL,
        ]);
    }
    public function test_cannot_get_geocode_correct_api_response_without_address()
    {
        $baseURL = env('APP_URL');
        $url = "{$baseURL}/api/geocode";

        $response = $this->get($url);

        $response->assertStatus(200);

        $this->assertArrayHasKey('error_message', $response->json());
        $this->assertArrayHasKey('results', $response->json());
        $this->assertArrayHasKey('status', $response->json());

        $response->assertJsonFragment([
            "error_message" => "Invalid request. Missing the 'address', 'components', 'latlng' or 'place_id' parameter.",
            "results" => [],
            "status" => "INVALID_REQUEST",
        ]);
    }

    public function test_geocode_api_returns_expected_response_with_address()
    {
        $address = "Budapest, Kerepesi út 124, 1144";
        $apiKey = env('VITE_GOOGLE_MAPS_API_KEY');
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $apiKey;

        Http::fake([
            '*' => Http::response([
                'results' => [
                    [
                        'formatted_address' => $address,
                        'geometry' => [
                            'location' => [
                                'lat' => 47.49801,
                                'lng' => 19.11361,
                            ],
                        ],
                    ],
                ],
                'status' => 'OK',
            ], 200),
        ]);

        $response = Http::get($url);
        $this->assertEquals(200, $response->status());

        $responseData = $response->json();
        $this->assertArrayHasKey('results', $responseData);
        $this->assertEquals('OK', $responseData['status']);
        $this->assertContains($address, array_column($responseData['results'], 'formatted_address'));
    }
}
