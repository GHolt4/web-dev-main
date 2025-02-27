{{-- resources/views/bikes/index.blade.php --}}
<x-layout>
    <x-slot:heading>
    
            Find Bike Page
    </x-slot:heading>
     
    <div class="container mx-auto px-4 py-8">
    
        {{-- Search Form --}}
    <div class="mb-8 bg-white p-6 rounded-lg shadow">
    <form action="" method="GET" class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
    <label for="subcategory" class="block text-sm font-medium text-gray-700">Subcategory</label>
    <select name="subcategory" id="subcategory" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    <option value="">All Subcategories</option>

        @foreach($subcategories as $subcategory)
        <option value="{{ $subcategory }}" 

        {{ request('subcategory') == $subcategory ? 'selected' : '' }}>

        {{ $subcategory }}
        </option>

        @endforeach
    </select>
    </div>
    <div>
    <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
    <select name="brand" id="brand" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    <option value="">All Brands</option>
    <!-- Add brands dynamically -->
    </select>
    </div>
    <div>
    <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
    <input type="number" name="year" id="year" value="{{ $year ?? date('Y') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    </div>
    <div class="flex justify-end h-40px">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
    
                        Search Bikes
    </button>
    </div>
    </form>
    </div>
    
        {{-- Results Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
            @forelse ($bikes ?? [] as $bike)
    <div class="bg-white rounded-lg shadow overflow-hidden">
    
                    @if($bike['image_url'] ?? false)
    <img src="{{ $bike['image_url'] }}" alt="{{ $bike['model'] }}" class="w-full h-48 object-cover">
    
                    @endif
    <div class="p-4">
    <h3 class="text-lg font-semibold">{{ $bike['model'] }}</h3>
    <p class="text-gray-600">{{ $bike['maker'] }} - {{ $bike['year'] }}</p>
    <p class="text-gray-800 mt-2">${{ number_format($bike['price'] ?? 0, 2) }}</p>
    <a href="{{ route('bikes.show', $bike['id']) }}" 
    
                           class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
    
                            View Details
    </a>
    </div>
    </div>
    
            @empty
    <div class="col-span-full text-center py-8">
    <p class="text-gray-500">No bikes found matching your criteria.</p>
    </div>
    
            @endforelse
    </div>
    </div>
    </x-layout>
     