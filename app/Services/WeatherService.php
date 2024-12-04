<?php

// app/Services/WeatherService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
    }

    public function getCurrentWeather($city)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric',
        ]);

        return $response->json();
    }

    public function getForecast($city)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric',
        ]);

        return $response->json();
    }

    // Nouvelle méthode pour récupérer les coordonnées
    public function getCityCoordinates($city)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'appid' => $this->apiKey,
        ]);

        // Extraire les coordonnées depuis la réponse
        $data = $response->json();

        if (isset($data['coord'])) {
            return [
                'lat' => $data['coord']['lat'],
                'lon' => $data['coord']['lon']
            ];
        }

        return null;
    }

    public function getFormattedForecast($city)
    {
        $forecast = $this->getForecast($city); // Suppose que getForecast récupère les données brutes
        return collect($forecast['list'])->map(function ($item) {
            return [
                'date' => $item['dt_txt'],
                'temperature' => $item['main']['temp'],
                'description' => $item['weather'][0]['description'],
                'wind_speed' => $item['wind']['speed'],
                'humidity' => $item['main']['humidity'],
            ];
        });
    }
}
