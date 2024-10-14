<?php

// app/Http/Controllers/WeatherController.php

namespace App\Http\Controllers;

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
        $city = $request->input('city', 'Dijon');
        $weather = $this->weatherService->getCurrentWeather($city);

        return view('weather.current', compact('weather'));
    }

    public function forecast(Request $request)
    {
        $city = $request->input('city', 'Dijon');
        $forecast = $this->weatherService->getForecast($city);

        return view('weather.forecast', compact('forecast'));
    }

    // Nouvelle méthode pour combiner la météo actuelle et les prévisions
    public function combined(Request $request)
    {
        $city = $request->input('city', 'Dijon');
        $weather = $this->weatherService->getCurrentWeather($city);
        $forecast = $this->weatherService->getForecast($city);

        return view('weather.combined', compact('weather', 'forecast', 'city'));
    }
}