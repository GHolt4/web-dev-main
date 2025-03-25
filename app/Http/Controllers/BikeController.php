<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchBikesRequest;

use App\Services\NinetyNineSpokesService;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use stdClass;


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
            
            Log::info('bikes search start', [

                'request' => $request->all(),

            ]);

            $year = $request->input('year', date('Y'));

            $bikes = [];

            // if ($request->hasAny(['subcategory', 'brand', 'year'])) {

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

            // }

            $subcategories = [];
            $brands = [];

            foreach ($bikes as $bike) {
                $subcategories[] = $bike['subcategory'];

                $brand = new stdClass;
                $brand->maker = $bike['maker'];
                $brand->makerId = $bike['makerId'];
                $brands[] = $brand;
            }

            // Remove duplicate subcategories
            $subcategories = array_unique($subcategories);
            $brands = array_unique($brands, SORT_REGULAR);


            return view('bikes.index', compact('bikes', 'subcategories', 'year', 'brands'));

        } catch (\Exception $e) {

            Log::error('Error fetching bikes:', [

                'error' => $e->getMessage()

            ]);

            return back()->with('error', 'An error occurred while fetching bikes.');

        }

    }

    public function store(Request $request)
    {
        // Validate the request if needed
        $request->validate([
            'bike_id' => 'required|string',
            'user_id' => 'required|integer',
        ]);
        // Insert into database
        DB::table('favourites')->insert([
            'user_id' => $request->user_id,
            'bike_id' => $request->bike_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Bike added successfully!');
    }

    public function showFavourites(NinetyNineSpokesService $service)
    {
        if (!Auth::check()) {
            // Handle unauthenticated users
            return redirect('/login');
        }
        try {
            $bikeIDs = DB::table('favourites')
                            // ->join('bikes', 'favourites.bike_id', '=', 'bikes.id')
                            ->where('favourites.user_id', Auth::id())
                            ->select('bike_id')
                            ->get();

// bike ids
            $favouriteBikes = [];

            foreach ($bikeIDs as $bikeID) {
                $response = $service->getBike($bikeID->bike_id);

                if (!$response->successful()) {

                    Log::error('99 Spokes API error', [

                        'status' => $response->status(),

                        'body' => $response->json()

                    ]);

                    return back()->with('error', 'Unable to fetch bikes. Please try again later.');

                }

                $favouriteBikes[] = $response->json();

                Log::info('bikes response 2:', [

                    'length' => count($favouriteBikes),

                    'bike' => $favouriteBikes

                ]);
            }

            // $favouriteBikes = $bikeIDs;

        } catch (\Exception $e) {
            // Log the error and return an empty collection
            Log::error('Error fetching favourite bikes: ' . $e->getMessage());
            $favouriteBikes = collect([]);
        }
        return view('favourites', ['favouriteBikes' => $favouriteBikes]);
    }
    
}
 