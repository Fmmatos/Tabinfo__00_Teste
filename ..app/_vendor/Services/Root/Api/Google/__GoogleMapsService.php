<?php

namespace Vendor\Services\Root\Api\Google;

use Illuminate\Support\Facades\Http;
use Vendor\Models\XSettings;

class __GoogleMapsService
{
    protected $apiKey;

    public function __construct()
    {
        $XSettings = XSettings::get__(['key_google']);
        $this->apiKey = $XSettings->key_google;
    }

    public function getRouteData(string $originLat, string $originLng, string $destLat, string $destLng): array
    {
        $response = Http::get('https://maps.googleapis.com/maps/api/directions/json', [
            'origin' => "{$originLat},{$originLng}",
            'destination' => "{$destLat},{$destLng}",
            'key' => $this->apiKey,
        ]);

        $data = $response->json();
        if (isset($data['routes'][0]['legs'][0]['distance']['value'])) {
            $data['routes'][0]['legs'][0]['distance']['value'] = $data['routes'][0]['legs'][0]['distance']['value']/1000;
            return $data['routes'][0]['legs'][0];

        } else {
            return [ 'errors' => $data ];
        }
    }
}
