<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather forecast for {{ $city }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>
    <h1>Weather forecast for {{ $city }}</h1>
    <p>Here is the weather forecast for the next days :</p>

    <table>
        <thead>
            <tr>
                <th>Date and time</th>
                <th>Temperature (°C)</th>
                <th>Description</th>
                <th>Wind (m/s)</th>
                <th>Humidity (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($forecast as $item)
            <tr>
                <td>{{ $item['date'] }}</td>
                <td>{{ $item['temperature'] }}</td>
                <td>{{ ucfirst($item['description']) }}</td>
                <td>{{ $item['wind_speed'] }}</td>
                <td>{{ $item['humidity'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>