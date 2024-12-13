<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather in {{ $weather['name'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #2980b9;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
        }

        .content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
            margin-bottom: 20px;
        }

        h1 {
            color: #2980b9;
            font-size: 2em;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2em;
            margin: 10px 0;
            color: #333;
        }

        form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            flex: 1;
        }

        button {
            padding: 10px 20px;
            font-size: 1em;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1abc9c;
        }

        .back-button {
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
    <form action="{{ route('weather.current') }}" method="GET">
        <input type="text" name="city" placeholder="Enter city name" value="{{ old('city') }}">
        <button type="submit">Search</button>
    </form>

    <div class="content">
        <h1>Current Weather in {{ $weather['name'] }}</h1>
        <p>Temperature: {{ $weather['main']['temp'] }} °C</p>
        <p>Weather: {{ $weather['weather'][0]['description'] }}</p>
        <a href="/" class="back-button">Back</a>
    </div>
</body>

</html>