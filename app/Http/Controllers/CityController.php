<?php

namespace App\Http\Controllers;

use App\Mail\WeatherForecastMail;
use App\Models\UserCity;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class CityController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    // Method to display the form for adding a city
    public function create()
    {
        return view('cities.add');  // Make sure you have the 'cities.add' view
    }

    // Method to display user’s cities
    public function index()
    {
        $userCities = UserCity::where('user_id', Auth::id())->get();
        return view('cities.index', compact('userCities'));
    }

    // Method to add a new city to the database
    public function store(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        UserCity::create([
            'user_id' => Auth::id(),
            'city' => $request->city,
        ]);

        return redirect()->route('cities.index')->with('success', 'City added successfully !');
    }

    // Putting a city in favor
    public function setFavorite($id)
    {
        $userId = Auth::id();

        // Retrieve the city to modify
        $city = UserCity::where('id', $id)->where('user_id', $userId)->firstOrFail();

        // If it is already favorite, deactivate it
        if ($city->favorite) {
            $city->favorite = false; // 0
            $city->save();
            return redirect()->route('cities.index')->with('success', "{$city->city} is no longer your favorite city!");
        }

        // Otherwise, disable all other user’s favorite cities
        UserCity::where('user_id', $userId)->where('favorite', true)->update(['favorite' => false]);

        // Mark current city as favorite
        $city->favorite = true; // 1
        $city->save();

        return redirect()->route('cities.index')->with('success', "{$city->city} is now your favorite city!");
    }

    public function sendForecast($cityId)
    {
        $userId = Auth::id();
        $city = UserCity::where('id', $cityId)->where('user_id', $userId)->firstOrFail();

        // Retrieve and format forecasts
        $forecast = $this->weatherService->getForecast($city->city);
        $formattedForecast = collect($forecast['list'])->map(function ($item) {
            return [
                'date' => $item['dt_txt'],
                'temperature' => $item['main']['temp'],
                'description' => $item['weather'][0]['description'],
                'wind_speed' => $item['wind']['speed'],
                'humidity' => $item['main']['humidity'],
            ];
        });

        // Create CSV data
        $csvData = [
            ['Date', 'Temperature', 'Description', 'Wind Speed', 'Humidity']
        ];
        foreach ($formattedForecast as $row) {
            $csvData[] = [
                $row['date'],
                $row['temperature'],
                $row['description'],
                $row['wind_speed'],
                $row['humidity'],
            ];
        }

        // Create the CSV in memory
        $fileName = "forecast_{$city->city}.csv";
        $handle = fopen('php://temp', 'w+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);

        // Attach the CSV to the email
        $email = new WeatherForecastMail($formattedForecast, $city->city);
        $email->attachData(stream_get_contents($handle), $fileName, [
            'mime' => 'text/csv',
        ]);

        // Send email
        Mail::to(Auth::user()->email)->send($email);

        fclose($handle);

        // Update the status to enable the "Cancel button
        $city->send_forecast = true;
        $city->save();

        return redirect()->route('cities.index')->with('success', "The weather forecast for {$city->city} were sent by email !");
    }

    public function scheduleForecast($cityId)
    {
        $userCity = UserCity::where('id', $cityId)->where('user_id', Auth::id())->firstOrFail();
        $userCity->send_forecast = true;
        $userCity->send_forecast_email_scheduled = now()->addDays(7);
        $userCity->save();

        return redirect()->route('cities.index')->with('success', "Forecast sent scheduled for {$userCity->city}.");
    }

    public function cancelForecast($cityId)
    {
        $userCity = UserCity::where('id', $cityId)->where('user_id', Auth::id())->firstOrFail();
        $userCity->send_forecast = false;
        $userCity->send_forecast_email_scheduled = null;
        $userCity->save();

        return redirect()->route('cities.index')->with('success', "Sending forecasts for {$userCity->city} was cancelled.");
    }

    public function destroy($id)
    {
        $userId = Auth::id();

        // Find the user’s city to delete
        $city = UserCity::where('id', $id)->where('user_id', $userId)->firstOrFail();

        // Delete city
        $city->delete();

        return redirect()->route('cities.index')->with('success', "The city {$city->city} has been removed successfully");
    }

    public function exportCityToCsv($cityId)
    {
        $userId = Auth::id();
        $city = UserCity::where('id', $cityId)->where('user_id', $userId)->firstOrFail();

        // Call the weather service to get the forecast
        $forecastData = $this->weatherService->getForecast($city->city);

        // Prepare data for CSV
        $csvData = [
            ['City', 'Date', 'Temperature', 'Description', 'Wind Speed', 'Humidity']
        ];

        foreach ($forecastData['list'] as $item) {
            $csvData[] = [
                $city->city,
                $item['dt_txt'],
                $item['main']['temp'],
                $item['weather'][0]['description'],
                $item['wind']['speed'],
                $item['main']['humidity']
            ];
        }

        // Create the CSV file
        $fileName = "forecast_{$city->city}.csv";
        $handle = fopen('php://memory', 'w');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);

        // Return CSV response
        return Response::stream(function () use ($handle) {
            fpassthru($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ]);
    }
}
