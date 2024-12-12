<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function current(Request $request)
    {
        $city = $request->input('city');

        if (!$city) {
            // If no city is searched, show the weather of the favorite city
            if (Auth::check()) {
                $favoriteCity = Auth::user()
                    ->userCities()
                    ->where('favorite', true)
                    ->first();

                if (!$favoriteCity) {
                    return redirect()->route('home')->with('error', 'No favorite city set. Please set a favorite city.');
                }

                $city = $favoriteCity->city;
            } else {
                return redirect()->route('home')->with('error', 'You must be logged in to view the weather.');
            }
        }

        // Call the service to get weather data
        $weather = $this->weatherService->getCurrentWeather($city);

        // API error check
        if (isset($weather['cod']) && $weather['cod'] != 200) {
            return redirect()->route('weather.current')->with('error', "Unable to fetch weather data for {$city}.");
        }

        return view('weather.current', compact('weather', 'city'));
    }


    public function forecast(Request $request)
    {
        $city = $request->input('city');

        if (!$city) {
            // If no city is searched, display the forecast of the favorite city
            if (Auth::check()) {
                $favoriteCity = Auth::user()
                    ->userCities()
                    ->where('favorite', true)
                    ->first();

                if (!$favoriteCity) {
                    return redirect()->route('home')->with('error', 'No favorite city set. Please set a favorite city.');
                }

                $city = $favoriteCity->city;
            } else {
                return redirect()->route('home')->with('error', 'You must be logged in to view the forecast.');
            }
        }

        // Call the service to get weather forecast data
        $forecast = $this->weatherService->getForecast($city);

        // API error check
        if (isset($forecast['cod']) && $forecast['cod'] != 200) {
            return redirect()->route('weather.forecast')->with('error', "Unable to fetch forecast data for {$city}.");
        }

        return view('weather.forecast', compact('forecast', 'city'));
    }


    public function combined(Request $request)
    {
        $city = $request->input('city', 'Dijon');
        $weather = $this->weatherService->getCurrentWeather($city);
        $forecast = $this->weatherService->getForecast($city);

        return view('weather.combined', compact('weather', 'forecast', 'city'));
    }

    // Method to search for the weather of a city
    public function search(Request $request)
    {
        // Validate that the user has provided a city
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        // Retrieve the city name from the query
        $city = $request->input('city');

        // Call the service to get city weather data
        $weather = $this->weatherService->getCurrentWeather($city);

        // Flip view with city weather data
        return view('home', compact('weather'));
    }
}
