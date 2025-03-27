<x-layout>
    <x-slot:heading>
        Reviews Page
    </x-slot:heading>

    @if(isset($reviewBikes) && !empty($reviewBikes))
    <h3>Reviews:</h3>
    <div class="w-full px-6 py-8 bg-gray-50">
        <div class="space-y-6">
            @foreach($reviewBikes as $index => $reviewBike)
                <div class="w-full flex flex-col md:flex-row bg-white shadow-lg rounded-2xl border border-gray-200 overflow-hidden">               
                    <!-- Text Content (75%) -->
                    <div class="w-full md:w-3/4 p-6">
                        <h4 class="text-2xl font-semibold text-gray-800">{{ $reviewBike['model'] ?? 'No Model' }}</h4>
                        <p class="text-gray-500">{{ $reviewBike['maker'] ?? 'No Maker' }}</p>
                        
                        @php
                            $bikeReviews = $reviews->filter(function($review) use ($reviewBike) {
                                return $review->bike_id == $reviewBike['id'];
                            });
                        @endphp

                        <ul class="space-y-2 text-base text-gray-700">
                            @forelse($bikeReviews as $review)
                                <li>
                                    <strong>Rating:</strong> {{ $review->rating }}/5
                                    <br>
                                    <strong>Review:</strong> {{ $review->review_text }}
                                </li>
                            @empty
                                <li>No reviews for this bike</li>
                            @endforelse
                        </ul>
                    </div>
        
                    <!-- Image Section (25%) -->
                    <img src="{{ $reviewBike['thumbnailUrl'] }}" alt="Bike Image" class="block mx-auto w-auto h-40">
                </div>
            @endforeach
        </div>
    </div>
    @else
        <p>There aren't any reviews yet!</p>
    @endif
</x-layout>