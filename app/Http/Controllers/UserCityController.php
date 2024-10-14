<?php

namespace App\Http\Controllers;

use App\Models\UserCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCityController extends Controller
{
    // Afficher les villes de l'utilisateur
    public function index()
    {
        $userCities = UserCity::where('user_id', Auth::id())->get();
        return response()->json($userCities);
    }

    // Ajouter une ville à la liste de l'utilisateur
    public function store(Request $request)
    {
        $request->validate(['city' => 'required|string|max:255']);

        $city = UserCity::create([
            'user_id' => Auth::id(),
            'city' => $request->city,
        ]);

        return response()->json(['message' => 'Ville ajoutée avec succès', 'city' => $city], 201);
    }

    // Supprimer une ville
    public function destroy($place)
    {
        $userCity = UserCity::where('city', $place)->where('user_id', Auth::id())->firstOrFail();
        $userCity->delete();

        return response()->json(['message' => 'Ville supprimée avec succès']);
    }

    // Marquer une ville comme favorite
    public function setFavorite($place)
    {
        $userId = Auth::id();

        // Supprimer l'ancienne ville favorite si elle existe
        UserCity::where('user_id', $userId)->where('favorite', true)->update(['favorite' => false]);

        // Marquer la nouvelle ville comme favorite
        $city = UserCity::where('city', $place)->where('user_id', $userId)->firstOrFail();
        $city->favorite = true;
        $city->save();

        return response()->json(['message' => "{$city->city} est maintenant votre ville favorite"]);
    }

    // Activer/désactiver l'envoi des prévisions par mail
    public function toggleSendForecast($place)
    {
        $userCity = UserCity::where('city', $place)->where('user_id', Auth::id())->firstOrFail();

        $userCity->send_forecast = !$userCity->send_forecast;
        $userCity->save();

        $message = $userCity->send_forecast ? 'Envoi des prévisions activé' : 'Envoi des prévisions désactivé';

        return response()->json(['message' => $message]);
    }
}
