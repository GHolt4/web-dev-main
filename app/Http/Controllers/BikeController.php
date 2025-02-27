<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchBikesRequest;

use App\Services\NinetyNineSpokesService;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\SimpleBikesController;


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

}
 