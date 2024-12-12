<?php

namespace App\Console\Commands;

use App\Models\UserCity;
use App\Services\WeatherService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeatherForecastMail;

class SendScheduledForecasts extends Command
{
    protected $signature = 'forecast:send';
    protected $description = 'Sends scheduled weather forecast to users';
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    public function handle()
    {
        $cities = UserCity::where('send_forecast', true)
            ->where('forecast_email_scheduled', '<=', now())
            ->get();

        foreach ($cities as $city) {
            $forecast = $this->weatherService->getForecast($city->city);
            Mail::to($city->user->email)->send(new WeatherForecastMail($forecast, $city->city));

            // Schedule next shipment in 7 days
            $city->forecast_email_scheduled = now()->addDays(7);
            $city->save();
        }

        return 0;
    }
}
