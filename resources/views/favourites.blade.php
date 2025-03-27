<x-layout>
    <x-slot:heading>
        Favourites Page
    </x-slot:heading>

    @if(isset($favouriteBikes) && count($favouriteBikes) > 0)
    <h3>Your Favourite Bikes:</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">    
        @foreach($favouriteBikes as $bike)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if($bike['image_url'] ?? false)
                    <img src="{{ $bike['image_url'] }}" alt="{{ $bike['model'] }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-4">
                    <h3 class="text-lg font-semibold">{{ $bike['model'] }}</h3>
                    <p class="text-gray-600">{{ $bike['maker'] }} - {{ $bike['year'] }}</p>
                    <p class="text-gray-600">{{ $bike['subcategory'] }}</p>
                    <img src="{{ $bike['thumbnailUrl'] }}" alt="Bike Image" class="block mx-auto w-64 h-auto">  
                    @php
                        $prices = $bike['prices'] ?? [];
                        $priceGBP = collect($prices)->firstWhere('currency', 'GBP')['amount'] ?? 0;
                    @endphp
                    @if ($priceGBP > 0)
                        <p class="text-gray-800 mt-2">£{{ number_format($priceGBP, 2) }}</p>
                    @else
                        <p class="text-gray-800 mt-2">Price not available</p>
                    @endif
                    <a href="{{ route('bikes.show', $bike['id']) }}"
                       class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        View Details
                    </a>
                    <button type="button" onclick="document.getElementById('review-form-{{ $bike['id'] }}-{{ $loop->iteration }}').classList.toggle('hidden')"
                            class="mt-2 inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Review
                    </button>
                    <div id="review-form-{{ $bike['id'] }}-{{ $loop->iteration }}" class="hidden mt-4">
                        <form action="{{ route('reviews.store', ['bikeId' => $bike['id']]) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label for="review-text-{{ $bike['id'] }}-{{ $loop->iteration }}" class="block text-sm font-medium text-gray-700">Your Review</label>
                                <textarea id="review-text-{{ $bike['id'] }}-{{ $loop->iteration }}" name="review_text" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                            </div>
                            <div class="mb-2">
                                <label for="rating-{{ $bike['id'] }}-{{ $loop->iteration }}" class="block text-sm font-medium text-gray-700">Rating</label>
                                <select id="rating-{{ $bike['id'] }}-{{ $loop->iteration }}" name="rating" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="" disabled selected>Select a rating</option>
                                    <option value="1">1 - Poor</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="3">3 - Good</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>
                            <button type="submit" class="mt-2 inline-block bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                Submit Review
                            </button>
                        </form>
                    </div>
                    <form action="{{ route('bikes.destroy', $bike['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this bike from your favourites?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="mt-2 inline-block bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    @else
        <p>You don't have any favourite bikes yet!</p>
    @endif
</x-layout>