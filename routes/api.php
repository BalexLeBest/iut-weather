<?php

use App\Http\Controllers\WeatherController;
use App\Http\Controllers\UserCityController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/weather', [WeatherController::class, 'current'])->name('api.weather.current');
    Route::get('/weather/forecast', [WeatherController::class, 'forecast'])->name('api.weather.forecast');
    Route::get('/users/places', [UserCityController::class, 'index'])->name('api.users.places');
    Route::post('/users/places', [UserCityController::class, 'store'])->name('api.users.places.add');
    Route::delete('/users/places/{place}', [UserCityController::class, 'destroy'])->name('api.users.places.delete');
    Route::patch('/users/places/{place}/favorite', [UserCityController::class, 'setFavorite'])->name('api.users.places.favorite');
    Route::patch('/users/places/{place}/send-forecast', [UserCityController::class, 'toggleSendForecast'])->name('api.users.places.send-forecast');
});
