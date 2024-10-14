<!-- resources/views/weather/current.blade.php -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather in {{ $weather['name'] }}</title>
</head>

<body>
    <h1>Current Weather in {{ $weather['name'] }}</h1>
    <p>Temperature: {{ $weather['main']['temp'] }} °C</p>
    <p>Weather: {{ $weather['weather'][0]['description'] }}</p>
</body>

</html>