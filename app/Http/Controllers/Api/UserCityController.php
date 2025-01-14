<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cities = $user->userCities()->paginate(10);

        return response()->json($cities);
    }

    public function store(Request $request)
    {
        $request->validate(['city' => 'required|string']);

        $user = Auth::user();
        $user->userCities()->create(['city' => $request->city]);

        return response()->json(['message' => 'City added successfully!'], 201);
    }

    public function toggleSendForecast($place)
    {
        $city = $this->findUserCity($place);
        $city->send_forecast = !$city->send_forecast;
        $city->save();

        return response()->json(['message' => "Send forecast toggled for {$city->city}."]);
    }

    public function toggleFavorite($place)
    {
        $user = Auth::user();
        $city = $this->findUserCity($place);

        // Remove favorite from other cities
        if (!$city->favorite) {
            $user->userCities()->where('favorite', true)->update(['favorite' => false]);
        }

        $city->favorite = !$city->favorite;
        $city->save();

        return response()->json(['message' => "{$city->city} favorite status updated."]);
    }

    public function destroy($place)
    {
        $city = $this->findUserCity($place);
        $city->delete();

        return response()->json(['message' => "City {$city->city} removed successfully."]);
    }

    protected function findUserCity($place)
    {
        return UserCity::where('user_id', Auth::id())->where('city', $place)->firstOrFail();
    }
}
