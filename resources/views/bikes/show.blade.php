{{-- resources/views/bikes/show.blade.php --}}
<x-layout>

    <x-slot:heading>
    
    Bike Details Page
</x-slot:heading>

    @section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                {{-- Bike Image --}}
                <div class="md:w-1/2">
                    @if($bike['image_url'] ?? false)
                    <img src="{{ $bike['image_url'] }}" alt="{{ $bike['model'] }}" class="w-full h-96 object-cover">
                    @endif
                </div>

                {{-- Bike Details --}}
                <div class="md:w-1/2 p-6">
                    <div class="mb-4">
                        <h1 class="text-3xl font-bold">{{ $bike['model'] }}</h1>
                        <p class="text-gray-600">{{ $bike['maker'] }} - {{ $bike['year'] }}</p>
                    </div>

                    {{-- <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-green-600">
                            ${{ number_format($bike['price'], 2) }}
                        </h2>
                    </div> --}}

                    {{-- Specifications --}}
                    <div class="space-y-4">
                        <h3 class="text-xl font-semibold">Specifications</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($bike['specifications'] ?? [] as $key => $value)
                            <div class="border-b pb-2">
                                <span class="font-medium text-gray-600">{{ $key }}:</span>
                                <span class="text-gray-800">{{ $value }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($bike['description'] ?? false)
                    <div class="mt-6">
                        <h3 class="text-xl font-semibold mb-2">Description</h3>
                        <p class="text-gray-700">{{ $bike['description'] }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
