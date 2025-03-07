<x-layout>
    <x-slot:heading>
        My Favorite Bikes
    </x-slot:heading>

    <div class="container mx-auto px-6 py-10">
        <div class="grid md:grid-cols-2 gap-8">
            @foreach($favorites as $favorite)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden p-6">
                    <h2 class="text-2xl font-bold">{{ $favorite->bike->model }}</h2>
                    <p>{{ $favorite->bike->maker }} - {{ $favorite->bike->year }}</p>
                    <a href="{{ route('bikes.show', $favorite->bike->id) }}" class="text-blue-500">View Details</a>
                </div>
            @endforeach
        </div>
    </div>
</x-layout>
