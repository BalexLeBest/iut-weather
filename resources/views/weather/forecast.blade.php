<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast for {{ $city }}</title>
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
            max-width: 600px;
            width: 90%;
            margin-bottom: 20px;
            overflow-y: auto;
            max-height: 80vh;
        }

        h1 {
            color: #2980b9;
            font-size: 2em;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #3498db;
            color: white;
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
    <form action="{{ route('weather.forecast') }}" method="GET">
        <input type="text" name="city" placeholder="Enter city name" value="{{ old('city') }}">
        <button type="submit">Search</button>
    </form>

    <div class="content">
        <h1>5-Day Weather Forecast for {{ $city }}</h1>
        <table>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Temperature (°C)</th>
                    <th>Description</th>
                    <th>Wind Speed (m/s)</th>
                    <th>Humidity (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($forecast['list'] as $item)
                <tr>
                    <td>{{ $item['dt_txt'] }}</td>
                    <td>{{ $item['main']['temp'] }}</td>
                    <td>{{ ucfirst($item['weather'][0]['description']) }}</td>
                    <td>{{ $item['wind']['speed'] }}</td>
                    <td>{{ $item['main']['humidity'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <a href="{{ url()->previous() }}" class="back-button">Back</a>
    </div>
</body>

</html>