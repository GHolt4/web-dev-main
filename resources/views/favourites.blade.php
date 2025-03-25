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
                </div>
            </div>
        @endforeach
    </div>
    @else
        <p>You don't have any favourite bikes yet!</p>
    @endif
</x-layout>