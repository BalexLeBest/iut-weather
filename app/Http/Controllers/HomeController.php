<?php

namespace App\Http\Controllers;

use App\Models\UserCity;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index()
    {
        $userId = Auth::id();

        // Retrieve the user’s favorite city
        $favoriteCity = UserCity::where('user_id', $userId)->where('favorite', true)->first();

        $forecast = null;
        if ($favoriteCity) {
            // Get weather forecast for favorite city
            $forecast = $this->weatherService->getForecast($favoriteCity->city);
        }
        
        $weather = null;

        return view('home', [
            'favoriteCity' => $favoriteCity,
            'forecast' => $forecast,
            'weather' => $weather,
        ]);
    }
}
