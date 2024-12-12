<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeatherForecastMail extends Mailable
{
    use Queueable, SerializesModels;

    public $forecast;
    public $city;

    public function __construct($forecast, $city)
    {
        $this->forecast = $forecast;
        $this->city = $city;
    }

    public function build()
    {
        return $this->subject("Weather forecast for {$this->city}")
            ->view('emails.weather_forecast');
    }
}
