<!-- resources/views/weather/forecast.blade.php -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forecast in {{ $forecast['city']['name'] }}</title>
</head>

<body>

    <h1>Weather Forecast for {{ $forecast['city']['name'] }}</h1>
    @foreach($forecast['list'] as $day)
    <p>{{ $day['dt_txt'] }} - Temp: {{ $day['main']['temp'] }} °C - {{ $day['weather'][0]['description'] }}</p>
    @endforeach

</body>

</html>