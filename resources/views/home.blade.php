<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6dd5fa, #2980b9);
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            min-height: 100vh;
        }

        h1,
        h2 {
            text-align: center;
            margin: 20px 0;
            color: white;
        }

        nav {
            background-color: #2c3e50;
            padding: 15px;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #3498db;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.15);
        }

        .auth-section {
            margin: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .auth-section a,
        .auth-section button {
            text-decoration: none;
            background-color: #1abc9c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .auth-section button {
            background-color: #e74c3c;
        }

        .auth-section a:hover,
        .auth-section button:hover {
            background-color: #16a085;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }

        body>form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 90%;
            max-width: 400px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            margin: 20px auto;
        }

        form label {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        form button:hover {
            background-color: #1abc9c;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: auto;
            width: 100%;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            background-color: #ffffffbb;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    @if (Route::has('login'))
    <div class="auth-section">
        @auth
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
        @else
        <a href="{{ route('login') }}">Login</a>
        @if (Route::has('register'))
        <a href="{{ route('register') }}">Register</a>
        @endif
        @endauth
    </div>
    @endif

    <nav>
        <ul>
            <li><a href="/weather/current">View Current Weather</a></li>
            <li><a href="/weather/forecast">View Weather Forecast</a></li>
            <li><a href="/cities">Manage Cities</a></li>
        </ul>
    </nav>

    <h1>Welcome to Your Weather Dashboard</h1>

    <form action="{{ route('weather.search') }}" method="GET">
        <label for="city">Search Weather by City:</label>
        <input type="text" name="city" id="city" placeholder="Enter city name">
        <button type="submit">Search</button>
    </form>

    @if(isset($weather) && isset($weather['name']) && isset($weather['main']['temp']) && isset($weather['weather'][0]['description']))
    <h2>Weather in {{ $weather['name'] }}</h2>
    <p>Temperature: {{ $weather['main']['temp'] }} °C</p>
    <p>Weather: {{ $weather['weather'][0]['description'] }}</p>
    @elseif(isset($weather))
    <p>Unable to fetch the weather for the city. Please check the city name.</p>
    @endif

    @if(isset($favoriteCity) && $favoriteCity)
    <h2>Weather Forecast for Your Favorite City: {{ $favoriteCity->city }}</h2>
    <ul>
        @foreach($forecast['list'] as $day)
        <li>{{ $day['dt_txt'] }} - Temp: {{ $day['main']['temp'] }} °C - {{ $day['weather'][0]['description'] }}</li>
        @endforeach
    </ul>
    @else
    <p>You haven't set a favorite city yet. Please set one to view the weather forecast here.</p>
    @endif

    <footer>
        &copy; 2024 Weather Dashboard
    </footer>
</body>

</html>
