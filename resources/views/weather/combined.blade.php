<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather in {{ $city }}</title>
</head>

<body>
    <h1>Weather in {{ $city }}</h1>

    <!-- current weather -->
    <h2>Current Weather</h2>
    <p>Temperature: {{ $weather['main']['temp'] }} °C</p>
    <p>Weather: {{ $weather['weather'][0]['description'] }}</p>
    <p>Humidity: {{ $weather['main']['humidity'] }}%</p>
    <p>Wind Speed: {{ $weather['wind']['speed'] }} m/s</p>

    <!-- weather forecast -->
    <h2>5-Day Forecast</h2>
    @foreach($forecast['list'] as $day)
        <p>{{ $day['dt_txt'] }} - Temp: {{ $day['main']['temp'] }} °C - {{ $day['weather'][0]['description'] }}</p>
    @endforeach
</body>

</html>
