<?php

namespace App\Console\Commands;

use App\Services\WeatherService;
use App\Mail\WeatherForecastMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendCustomForecast extends Command
{
    /**
     * The signature defines the name of the command and its arguments/parameters
     *
     * In this example, we define:
     *   - {city} as a mandatory argument (name of city)
     *   - {email} as a mandatory argument (email address)
     */
    protected $signature = 'forecast:send-custom {city} {email}';

    /**
     * Description of the command (displayed with php artisan list).
     */
    protected $description = 'Send the forecast of a given city to a given email address.';

    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    /**
     * Method handle() executed when the command is run.
     */
    public function handle()
    {
        // 1. Retrieval of arguments
        $city = $this->argument('city');
        $email = $this->argument('email');

        // 2. Forecast retrieval via WeatherService
        $forecastData = $this->weatherService->getForecast($city);

        // Basic check if the API returns an error (ex: city not found)
        if (isset($forecastData['cod']) && $forecastData['cod'] != 200) {
            $this->error("Unable to retrieve forecast for {$city}.");
            return 1; // code d’erreur
        }

        // 3. Conversion to a format usable for CSV 
        //    (in the example, we retrieve the forecast date/temperature/description etc.)
        $formattedForecast = collect($forecastData['list'])->map(function ($item) {
            return [
                'date' => $item['dt_txt'],
                'temperature' => $item['main']['temp'],
                'description' => $item['weather'][0]['description'],
                'wind_speed' => $item['wind']['speed'],
                'humidity' => $item['main']['humidity'],
            ];
        });

        // 4. Creation of a CSV "in memory
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

        // Opening a memory stream
        $handle = fopen('php://temp', 'w+');
        // Writing the CSV
        foreach ($csvData as $fields) {
            fputcsv($handle, $fields);
        }
        rewind($handle); // we replace at the beginning to be able to read it again

        // 5. Building the mail, using your existing WeatherForecastMail mailable
        //    (or other mailable if you prefer)
        $emailMailable = new WeatherForecastMail($formattedForecast, $city);

        // On attach le CSV directement via attachData() en lui passant le flux
        $emailMailable->attachData(
            stream_get_contents($handle), 
            "forecast_{$city}.csv", // file name
            ['mime' => 'text/csv']
        );

        fclose($handle); // we can close the flow

        // 6. Sending the mail via Mail::to()
        Mail::to($email)->send($emailMailable);

        // 7. End message in console
        $this->info("Forecast for {$city} has been sent to {$email} successfully.");

        return 0; // everything went well
    }
}
