<?php

namespace App\Http\Controllers;

use App\Mail\WeatherForecastMail;
use App\Models\UserCity;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CityController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    // Méthode pour afficher le formulaire d'ajout d'une ville
    public function create()
    {
        return view('cities.add');  // Assure-toi d'avoir la vue 'cities.add'
    }

    // Méthode pour afficher les villes de l'utilisateur
    public function index()
    {
        $userCities = UserCity::where('user_id', Auth::id())->get();
        return view('cities.index', compact('userCities'));
    }

    // Méthode pour ajouter une nouvelle ville à la base de données
    public function store(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        UserCity::create([
            'user_id' => Auth::id(),
            'city' => $request->city,
        ]);

        return redirect()->route('cities.index')->with('success', 'Ville ajoutée avec succès !');
    }

    // Mettre une ville en favorie
    public function setFavorite($id)
    {
        $userId = Auth::id();

        // Supprimer l'ancienne ville favorite si elle existe
        UserCity::where('user_id', $userId)->where('favorite', true)->update(['favorite' => false]);

        // Marquer la nouvelle ville comme favorite
        $city = UserCity::where('id', $id)->where('user_id', $userId)->first();
        if ($city) {
            $city->favorite = true;
            $city->save();
        }

        return redirect()->route('cities.index')->with('success', "{$city->city} est maintenant votre ville favorite !");
    }

    // Méthode pour envoyer les prévisions par email
    public function sendForecast($cityId)
    {
        $userId = Auth::id();  // Récupérer l'utilisateur connecté
        $city = UserCity::where('id', $cityId)->where('user_id', $userId)->firstOrFail();

        // Récupérer les prévisions météo pour cette ville
        $forecast = $this->weatherService->getForecast($city->city);

        // Envoyer l'email avec les prévisions à l'utilisateur connecté
        Mail::to(Auth::user()->email)->send(new WeatherForecastMail($forecast, $city->city));

        return redirect()->route('cities.index')->with('success', "Les prévisions météo pour {$city->city} ont été envoyées par email !");
    }



    public function destroy($id)
    {
        $userId = Auth::id();

        // Trouver la ville de l'utilisateur à supprimer
        $city = UserCity::where('id', $id)->where('user_id', $userId)->firstOrFail();

        // Supprimer la ville
        $city->delete();

        return redirect()->route('cities.index')->with('success', "La ville {$city->city} a été supprimée avec succès");
    }
}
