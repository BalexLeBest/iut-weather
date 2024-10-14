<!-- resources/views/emails/weather_forecast.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Prévisions météo pour {{ $city }}</title>
</head>
<body>
    <h1>Prévisions météo pour {{ $city }}</h1>

    @foreach($forecast['list'] as $day)
        <p>{{ $day['dt_txt'] }} - Température : {{ $day['main']['temp'] }} °C - {{ $day['weather'][0]['description'] }}</p>
    @endforeach
</body>
</html>