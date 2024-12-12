<?php

namespace App\Http\Controllers;

use App\Models\UserCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCityController extends Controller
{
    // Show user’s cities
    public function index()
    {
        $userCities = UserCity::where('user_id', Auth::id())->get();
        return response()->json($userCities);
    }

    // Add a city to the user’s list
    public function store(Request $request)
    {
        $request->validate(['city' => 'required|string|max:255']);

        $city = UserCity::create([
            'user_id' => Auth::id(),
            'city' => $request->city,
        ]);

        return response()->json(['message' => 'Ville ajoutée avec succès', 'city' => $city], 201);
    }

    // remove a city
    public function destroy($place)
    {
        $userCity = UserCity::where('city', $place)->where('user_id', Auth::id())->firstOrFail();
        $userCity->delete();

        return response()->json(['message' => 'Ville supprimée avec succès']);
    }

    // Mark a city as favorite
    public function setFavorite($place)
    {
        $userId = Auth::id();

        // Delete old favorite city if it exists
        UserCity::where('user_id', $userId)->where('favorite', true)->update(['favorite' => false]);

        // Mark new city as favorite
        $city = UserCity::where('city', $place)->where('user_id', $userId)->firstOrFail();
        $city->favorite = true;
        $city->save();

        return response()->json(['message' => "{$city->city} est maintenant votre ville favorite"]);
    }

    // Enable/disable sending of forecasts by email
    public function toggleSendForecast($place)
    {
        $userCity = UserCity::where('city', $place)->where('user_id', Auth::id())->firstOrFail();

        $userCity->send_forecast = !$userCity->send_forecast;
        $userCity->save();

        $message = $userCity->send_forecast ? 'Sending forecasts enabled' : 'Sending forecasts disabled';

        return response()->json(['message' => $message]);
    }
}
