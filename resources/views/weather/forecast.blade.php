<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forecast in {{ $forecast['city']['name'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #2980b9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 90%;
            overflow-y: auto;
            max-height: 80vh;
        }

        .content h1 {
            color: #2980b9;
            font-size: 2em;
            margin-bottom: 20px;
        }

        .content p {
            font-size: 1em;
            margin: 10px 0;
            color: #333;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            font-size: 1.1em;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #1abc9c;
        }
    </style>
</head>

<body>
    <div class="content">
        <h1>Weather Forecast for {{ $forecast['city']['name'] }}</h1>
        @foreach($forecast['list'] as $day)
        <p>{{ $day['dt_txt'] }} - Temp: {{ $day['main']['temp'] }} °C - {{ $day['weather'][0]['description'] }}</p>
        @endforeach
        <a href="{{ url()->previous() }}" class="back-button">Retour</a>
    </div>
</body>

</html>
