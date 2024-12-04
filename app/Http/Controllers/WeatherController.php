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

    public function combined(Request $request)
    {
        $city = $request->input('city', 'Dijon');
        $weather = $this->weatherService->getCurrentWeather($city);
        $forecast = $this->weatherService->getForecast($city);

        return view('weather.combined', compact('weather', 'forecast', 'city'));
    }

    // Méthode pour rechercher la météo d'une ville
    public function search(Request $request)
    {
        // Valider que l'utilisateur a bien fourni une ville
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        // Récupérer le nom de la ville depuis la requête
        $city = $request->input('city');

        // Appeler le service pour obtenir les données météo de la ville
        $weather = $this->weatherService->getCurrentWeather($city);

        // Retourner la vue avec les données météo de la ville
        return view('home', compact('weather'));
    }
}