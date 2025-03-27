<?php

namespace App\Http\Controllers;

use App\Services\NinetyNineSpokesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController
{
    public function show($id)
    {
        // Add logic to retrieve and display the review by ID
        return view('reviews.show', ['id' => $id]);
    }

    public function store(Request $request, $bikeId)
    {
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string',
        ]);

        // Retrieve the favourites record
        $favourite = DB::table('favourites')
            ->where('bike_id', $bikeId)
            ->first();

        if (!$favourite) {
            return back()->withErrors(['bikeId' => 'Invalid bikeId or no matching favourite found.']);
        }

        // Insert the review
        $review = DB::table('reviews')->insertGetId([
            'user_id' => $favourite->user_id,
            'bike_id' => $favourite->bike_id,
            'favourites_id' => $favourite->id,
            'review_text' => $validatedData['review_text'],
            'rating' => $validatedData['rating'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('reviews.index')
        ->with('success', 'Review created successfully!');

    }

    public function index(NinetyNineSpokesService $service)
    {
        $reviews = DB::table('reviews')->select('*')->get();
        // Retrieve only the bike_id column from the reviews table
        $bikeIds = DB::table('reviews')->pluck('bike_id');

        $reviewBikes = [];

        foreach ($bikeIds as $bikeID) {
            $response = $service->getBike($bikeID);

            if (!$response->successful()) {
                Log::error('99 Spokes API error', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);

                return back()->with('error', 'Unable to fetch bikes. Please try again later.');
            }

            $reviewBikes[] = $response->json();
        }

        Log::info('bikes response:', [
            'length' => count($reviewBikes),
            'bikes' => $reviewBikes
        ]);

        return view('reviews', ['reviewBikes' => $reviewBikes, 'reviews' => $reviews]);
    }
}