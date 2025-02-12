<?php

use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\UserCityController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Generate a Sanctum token for the user
    $token = $user->createToken('API Token')->plainTextToken;

    // /api/login
    // {
    //     "email": "email@exemple.com",
    //     "password": "**password**"
    //   }

    return response()->json(['token' => $token]);
});

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/weather', [WeatherController::class, 'current'])->name('api.weather.current');
    //GET http://127.0.0.1:8000/api/v1/weather
    // {
    //     "place": "Rumilly"
    //   }

    Route::get('/weather/forecast', [WeatherController::class, 'forecast'])->name('api.weather.forecast');
    // GET http://127.0.0.1:8000/api/v1/weather/forecast
    // {
    //     "place": "Rumilly"
    //   }

    Route::get('/users/places', [UserCityController::class, 'index'])->name('api.users.places');
    // GET http://127.0.0.1:8000/api/v1/users/places

    Route::post('/users/places', [UserCityController::class, 'store'])->name('api.users.places.add');
    // POST http://127.0.0.1:8000/api/v1/users/places
    // {
    //     "city": "Rumilly"
    //   }
    
    Route::delete('/users/places/{place}', [UserCityController::class, 'destroy'])->name('api.users.places.delete');
    // DELETE http://127.0.0.1:8000/api/v1/users/places/Rumilly

    Route::patch('/users/places/{place}/favorite', [UserCityController::class, 'toggleFavorite'])->name('api.users.places.favorite');
    // PATCH http://127.0.0.1:8000/api/v1/users/places/Caen/send-forecast

    Route::patch('/users/places/{place}/send-forecast', [UserCityController::class, 'toggleSendForecast'])->name('api.users.places.send-forecast');
    // PATCH http://127.0.0.1:8000/api/v1/users/places/Caen/favorite

});
