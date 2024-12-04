<?php

// routes/web.php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\CityController;

Route::middleware(['auth'])->group(function () {
    // Afficher la liste des villes
    Route::get('/cities', [CityController::class, 'index'])->name('cities.index');

    // Afficher le formulaire pour ajouter une ville
    Route::get('/cities/add', [CityController::class, 'create'])->name('cities.add');

    // Sauvegarder la nouvelle ville
    Route::post('/cities', [CityController::class, 'store'])->name('cities.store');

    // Route pour définir une ville comme favorite
    Route::post('/cities/{id}/favorite', [CityController::class, 'setFavorite'])->name('cities.favorite');

    // Route pour envoyer les prévisions météo par email
    Route::post('/cities/{id}/send-forecast', [CityController::class, 'sendForecast'])->name('cities.send-forecast');

    // Route pour supprimer une ville
    Route::delete('/cities/{id}', [CityController::class, 'destroy'])->name('cities.destroy');

    Route::post('/cities/{id}/send-forecast', [CityController::class, 'sendForecast'])->name('cities.send-forecast');
    
    Route::post('/cities/{id}/schedule-forecast', [CityController::class, 'scheduleForecast'])->name('cities.scheduleForecast');

    // Annuler l'envoi des prévisions
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
