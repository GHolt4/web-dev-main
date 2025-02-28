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
                <div id="carousel-wrapper" class="overflow-hidden md:w-1/2 h-auto relative">
                    <div id="carousel" class="flex transition-transform duration-500">
                        @foreach($bike['images'] as $image)
                            <img src="{{ $image['url'] }}" alt="{{ $bike['model'] }}" class="w-full h-auto object-contain min-w-full">
                        @endforeach
                    </div>
                    <button id="prev" class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-gray-900 text-white p-2 rounded-full hover:bg-yellow-500 transition">
                        &#8592;
                    </button>
                    <button id="next" class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-gray-900 text-white p-2 rounded-full hover:bg-yellow-500 transition">
                        &#8594;
                    </button>
                </div>                

                {{-- Bike Details --}}
                <div class="md:w-1/2 p-6">
                    <div class="mb-4">
                        <h1 class="text-3xl font-bold">{{ $bike['model'] }}</h1>
                        <p class="text-gray-600">{{ $bike['maker'] }} - {{ $bike['year'] }}</p>
                        <p class="text-gray-600">{{ $bike['category'] }}</p>
                        <p class="text-gray-600">{{ $bike['subcategory'] }}</p>
                        @php
                            $prices = $bike['prices'] ?? [];
                            $priceGBP = collect($prices)->firstWhere('currency', 'GBP')['amount'] ?? 0;
                        @endphp
                        <h3 class="text-gray-800 mt-2">£{{ number_format($priceGBP, 2) }}</h3>
                    </div>

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
    <script>
    document.addEventListener("DOMContentLoaded", function () {
    const carousel = document.getElementById("carousel");
    const images = carousel.children;
    const totalImages = images.length;
    let currentIndex = 0;

    const nextButton = document.getElementById("next");
    const prevButton = document.getElementById("prev");

    function updateCarousel() {
        carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    nextButton.addEventListener("click", function () {
        currentIndex = (currentIndex + 1) % totalImages;
        updateCarousel();
    });

    prevButton.addEventListener("click", function () {
        currentIndex = (currentIndex - 1 + totalImages) % totalImages;
        updateCarousel();
    });
});
</script>
</x-layout>
