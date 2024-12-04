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

        // Récupérer la ville favorite de l'utilisateur
        $favoriteCity = UserCity::where('user_id', $userId)->where('favorite', true)->first();

        $forecast = null;
        if ($favoriteCity) {
            // Récupérer les prévisions météo pour la ville favorite
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
