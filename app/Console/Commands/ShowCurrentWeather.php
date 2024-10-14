<?php

namespace App\Console\Commands;

use App\Services\WeatherService;
use Illuminate\Console\Command;

class ShowCurrentWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:current {city}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Affichage de la météo courante pour une ville donnée';
    protected $weatherService;

    public function __construct(WeatherService $weatherService){
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    public function handle(){
        $city = $this->argument('city');

        $weather = $this->weatherService->getCurrentWeather($city);

        if(isset($weather['cod']) && $weather['cod'] != 200){
            $this->error("Impossible de récupérer la météo pour {$city}. Veuillez vérifier le nom de la ville.");
            return 1;
        }

        $this->info("Méteo courante pour {$city} : ");
        $this->line("Température : {$weather['main']['temp']} °C");
        $this->line("Description : {$weather['weather'][0]['description']}");
        $this->line("Humidité : {$weather['main']['humidity']}%");
        $this->line("Vitesse du vent : {$weather['wind']['speed']} m/s");

        return 0;
    }
}