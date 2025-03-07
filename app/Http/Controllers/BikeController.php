<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchBikesRequest;
use App\Services\NinetyNineSpokesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\SimpleBikesController;
use Illuminate\Support\Facades\Auth;
use App\Models\Bike;

class BikeController extends Controller
{
    public function show(NinetyNineSpokesService $service, string $bikeId)
    {
        $response = $service->getBike($bikeId);

        if ($response->successful()) {
            return view('bikes.show', [
                'bike' => $response->json()
            ]);
        }

        return back()->with('error', 'Unable to fetch bike details');
    }

    public function search(SearchBikesRequest $request, NinetyNineSpokesService $service)
    {
        try {
            $year = $request->input('year', date('Y'));
            $bikes = [];

            if ($request->hasAny(['subcategory', 'brand', 'year'])) {
                $response = $service->searchBikes($request->validated());

                if (!$response->successful()) {
                    Log::error('99 Spokes API error', [
                        'status' => $response->status(),
                        'body' => $response->json()
                    ]);

                    return back()->with('error', 'Unable to fetch bikes. Please try again later.');
                }

                $bikes = $response->json()['items'] ?? [];

                Log::info('bikes response 2:', [
                    'length' => count($bikes),
                ]);
            }

            // extract all the subcategories from the bikes
            $subcategories = [];
            $brands = [];

            foreach ($bikes as $bike) {
                $subcategories[] = $bike['subcategory'];
                $brands[] = $bike['maker'];
            }

            // Remove duplicate subcategories
            $subcategories = array_unique($subcategories);
            $brands = array_unique($brands);

            return view('bikes.index', compact('bikes', 'subcategories', 'year', 'brands'));
        } catch (\Exception $e) {
            Log::error('Error fetching bikes:', [
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'An error occurred while fetching bikes.');
        }
    }

    public function favorite(Request $request, NinetyNineSpokesService $service, $bikeId)
    {
        try {
            $user = Auth::user();
            Log::info('User attempting to favorite bike:', ['user_id' => $user->id, 'bike_id' => $bikeId]);

            $response = $service->getBike($bikeId);

            if (!$response->successful()) {
                Log::error('99 Spokes API error while fetching bike for favoriting:', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);

                return back()->with('error', 'Unable to fetch bike details. Please try again later.');
            }

            $bike = $response->json();

            // Assuming you have a method to save the bike details locally if needed
            // $savedBike = Bike::updateOrCreate(['id' => $bikeId], $bike);

            $user->favorites()->attach($bikeId);

            Log::info('Bike favorited successfully:', ['user_id' => $user->id, 'bike_id' => $bikeId]);

            return back()->with('success', 'Bike favorited successfully.');
        } catch (\Exception $e) {
            Log::error('Error favoriting bike:', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'bike_id' => $bikeId
            ]);

            return back()->with('error', 'An error occurred while favoriting the bike.');
        }
    }
}
