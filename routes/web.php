<?php

// routes/web.php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\CityController;

Route::middleware(['auth'])->group(function () {
    // Show list of cities
    Route::get('/cities', [CityController::class, 'index'])->name('cities.index');

    // Display the form to add a city
    Route::get('/cities/add', [CityController::class, 'create'])->name('cities.add');

    // Save the new city
    Route::post('/cities', [CityController::class, 'store'])->name('cities.store');

    // Route to define a city as favorite
    Route::post('/cities/{id}/favorite', [CityController::class, 'setFavorite'])->name('cities.favorite');

    // Route to send weather forecast by email
    Route::post('/cities/{id}/send-forecast', [CityController::class, 'sendForecast'])->name('cities.send-forecast');

    // Route to delete a city
    Route::delete('/cities/{id}', [CityController::class, 'destroy'])->name('cities.destroy');

    Route::post('/cities/{id}/send-forecast', [CityController::class, 'sendForecast'])->name('cities.send-forecast');
    
    Route::post('/cities/{id}/schedule-forecast', [CityController::class, 'scheduleForecast'])->name('cities.scheduleForecast');

    // Cancel sending of forecast
    Route::post('/cities/{id}/cancel-forecast', [CityController::class, 'cancelForecast'])->name('cities.cancelForecast');
});


Route::get('/', function () {
    return view('home');
});

Route::get('/weather/current', [WeatherController::class, 'current'])->name('weather.current');
Route::get('/weather/forecast', [WeatherController::class, 'forecast'])->name('weather.forecast');
Route::get('/weather/coordinates', [WeatherController::class, 'coordinates'])->name('weather.coordinates');
Route::get('/weather/combined', [WeatherController::class, 'combined'])->name('weather.combined');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/weather/search', [WeatherController::class, 'search'])->name('weather.search');
Route::get('/cities/{id}/download', [CityController::class, 'exportCityToCsv'])->name('cities.download');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__ . '/auth.php';
