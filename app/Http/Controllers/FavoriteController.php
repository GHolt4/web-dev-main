<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        Log::info('FavoriteController@store called', ['request' => $request->all()]);

        $request->validate([
            'bike_id' => 'required|string|exists:bikes,id',
        ]);

        Log::info('Validation passed', ['user_id' => Auth::id(), 'bike_id' => $request->bike_id]);

        // Check if the favorite already exists
        $existingFavorite = Favorite::where('user_id', Auth::id())
                                    ->where('bike_id', $request->bike_id)
                                    ->first();

        if ($existingFavorite) {
            Log::info('Bike already in favorites', ['user_id' => Auth::id(), 'bike_id' => $request->bike_id]);
            return response()->json(['success' => false, 'message' => 'Bike already in favorites']);
        }

        $favorite = new Favorite();
        $favorite->user_id = Auth::id();
        $favorite->bike_id = $request->bike_id;
        $favorite->save();

        Log::info('Bike added to favorites', ['user_id' => Auth::id(), 'bike_id' => $request->bike_id]);

        return response()->json(['success' => true]);
    }

    public function index()
    {
        $favorites = Auth::user()->favorites()->with('bike')->get();
        return view('favorites.index', compact('favorites'));
    }
}
