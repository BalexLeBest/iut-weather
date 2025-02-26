<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your cities</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #3498db;
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
            max-width: 900px;
            width: 90%;
        }

        h1 {
            color: #2980b9;
            font-size: 2em;
            margin-bottom: 20px;
        }

        .success-message {
            background-color: #1abc9c;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            background-color: #ecf0f1;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        ul li form {
            display: inline;
        }

        button {
            padding: 10px 15px;
            font-size: 1em;
            font-weight: bold;
            color: white;
            background-color: #3498db;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        a.add-city,
        a.back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #1abc9c;
            color: white;
            font-size: 1.1em;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        a.add-city:hover,
        a.back-btn:hover {
            background-color: #16a085;
        }

        .back-btn {
            background-color: #e74c3c;
            margin-top: 10px;
        }

        .back-btn:hover {
            background-color: #c0392b;
        }

        .download-button {
            padding: 10px 15px;
            font-size: 1em;
            font-weight: bold;
            color: white;
            background-color: #1abc9c;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .download-button:hover {
            background-color: #16a085;
        }

        li{
            display: flex;
        }
        li>span{
            flex-direction: start;
        }

        .info{
            flex-direction: end;
        }
        
    </style>
</head>

<body>
    <div class="content">
        <h1>Your cities</h1>

        @if (session('success'))
        <div class="success-message">{{ session('success') }}</div>
        @endif

        <ul>
            @foreach ($userCities as $city)
            <li>
                <span>{{ $city->city }}</span>

                <!-- Indicate if the city is a favorite -->
                <div id="info">
                    @if ($city->favorite)
                    <strong>(Favorite)</strong>
                    <!-- Button to remove favorite -->
                    <form action="{{ route('cities.favorite', $city->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">Remove Favorite</button>
                    </form>
                    @else
                    <!-- Button to mark as favorite -->
                    <form action="{{ route('cities.favorite', $city->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">Set as Favorite</button>
                    </form>
                    @endif

                    <form action="{{ route('cities.destroy', $city->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete City</button>
                    </form>
                    @if ($city->send_forecast)
                    <form action="{{ route('cities.cancelForecast', $city->id) }}" method="POST">
                        @csrf
                        <button type="submit">Cancel the sending</button>
                    </form>
                    @else
                    <form action="{{ route('cities.send-forecast', $city->id) }}" method="POST">
                        @csrf
                        <button type="submit">Send the forecast</button>
                    </form>
                    @endif
                    <a href="{{ route('cities.download', $city->id) }}" class="download-button">Download CSV</a>
                </div>
            </li>
            @endforeach
        </ul>

        <a href="{{ route('cities.add') }}" class="add-city">Add a new city</a>
        <a href="/" class="back-btn">Back</a>
    </div>
</body>

</html>