<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function current(Request $request)
    {
        $request->validate(['place' => 'required|string']);

        $place = $request->input('place');
        $weather = $this->weatherService->getCurrentWeather($place);

        return response()->json($weather);
    }

    public function forecast(Request $request)
    {
        $request->validate(['place' => 'required|string']);

        $place = $request->input('place');
        $forecast = $this->weatherService->getForecast($place);

        if (isset($forecast['cod']) && $forecast['cod'] != 200) {
            return response()->json(['error' => "Unable to fetch forecast for {$place}."], 400);
        }

        return response()->json($forecast);
    }
}
