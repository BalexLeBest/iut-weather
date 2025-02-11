<?php

namespace App\Console\Commands;

use App\Services\WeatherService;
use App\Mail\WeatherForecastMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendCustomForecast extends Command
{
    /**
     * La signature définit le nom de la commande et ses arguments/paramètres
     *
     * Dans cet exemple, on définit :
     *   - {city} comme argument obligatoire (nom de la ville)
     *   - {email} comme argument obligatoire (adresse email)
     */
    protected $signature = 'forecast:send-custom {city} {email}';

    /**
     * Description de la commande (affichée avec php artisan list).
     */
    protected $description = 'Send the forecast of a given city to a given email address.';

    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    /**
     * Méthode handle() exécutée quand on lance la commande.
     */
    public function handle()
    {
        // 1. Récupération des arguments
        $city = $this->argument('city');
        $email = $this->argument('email');

        // 2. Récupération des prévisions via le WeatherService
        $forecastData = $this->weatherService->getForecast($city);

        // Vérification basique si l’API retourne une erreur (ex: ville introuvable)
        if (isset($forecastData['cod']) && $forecastData['cod'] != 200) {
            $this->error("Unable to retrieve forecast for {$city}.");
            return 1; // code d’erreur
        }

        // 3. Conversion en un format exploitable pour le CSV 
        //    (dans l’exemple, on récupère les prévisions date/température/description etc.)
        $formattedForecast = collect($forecastData['list'])->map(function ($item) {
            return [
                'date' => $item['dt_txt'],
                'temperature' => $item['main']['temp'],
                'description' => $item['weather'][0]['description'],
                'wind_speed' => $item['wind']['speed'],
                'humidity' => $item['main']['humidity'],
            ];
        });

        // 4. Création d’un CSV "en mémoire"
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

        // Ouverture d’un flux mémoire
        $handle = fopen('php://temp', 'w+');
        // Ecriture du CSV
        foreach ($csvData as $fields) {
            fputcsv($handle, $fields);
        }
        rewind($handle); // on se replace au début pour pouvoir le relire

        // 5. Construction du mail, en utilisant votre Mailable existant WeatherForecastMail
        //    (ou un autre Mailable si vous préférez)
        $emailMailable = new WeatherForecastMail($formattedForecast, $city);

        // On attache le CSV directement via attachData() en lui passant le flux
        $emailMailable->attachData(
            stream_get_contents($handle), 
            "forecast_{$city}.csv", // nom du fichier
            ['mime' => 'text/csv']
        );

        fclose($handle); // on peut fermer le flux

        // 6. Envoi du mail via Mail::to()
        Mail::to($email)->send($emailMailable);

        // 7. Message de fin dans la console
        $this->info("Forecast for {$city} has been sent to {$email} successfully.");

        return 0; // tout s’est bien passé
    }
}
