<!-- resources/views/weather/coordinates.blade.php -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinates of {{ $city }}</title>
</head>

<body>

    <h1>Coordinates for {{ $city }}</h1>

    @if($coordinates)
        <p>Latitude: {{ $coordinates['lat'] }}</p>
        <p>Longitude: {{ $coordinates['lon'] }}</p>
    @else
        <p>Coordinates not found for {{ $city }}</p>
    @endif

</body>

</html>
