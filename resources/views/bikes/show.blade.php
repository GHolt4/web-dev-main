<x-layout>
    <x-slot:heading>
        Bike Details Page
    </x-slot:heading>

    <div class="container mx-auto px-6 py-10">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden p-6">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Bike Image Carousel -->
                <div class="relative overflow-hidden rounded-lg">
                    <div id="carousel" class="flex transition-transform duration-500">
                        @foreach($bike['images'] as $image)
                            <img src="{{ $image['url'] }}" alt="{{ $bike['model'] }}" class="w-full h-auto object-contain rounded-lg">
                        @endforeach
                    </div>
                    <button id="prev" class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-black text-white p-2 rounded-full hover:bg-yellow-500 transition">
                        &#8592;
                    </button>
                    <button id="next" class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-black text-white p-2 rounded-full hover:bg-yellow-500 transition">
                        &#8594;
                    </button>
                </div>
                
                <!-- Bike Details -->
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">{{ $bike['model'] }}</h1>
                    <p class="text-lg text-gray-600">{{ $bike['maker'] }} - {{ $bike['year'] }}</p>
                    <p class="text-gray-600 font-medium">{{ $bike['category'] }} | {{ $bike['subcategory'] }}</p>
                    
                    @php
                        $prices = $bike['prices'] ?? [];
                        $priceGBP = collect($prices)->firstWhere('currency', 'GBP')['amount'] ?? 0;
                    @endphp
                    <h3 class="text-2xl font-semibold text-gray-800 mt-3">£{{ number_format($priceGBP, 2) }}</h3>

                    <!-- Specifications -->
                    <div class="mt-4 space-y-2">
                        <h3 class="text-xl font-semibold">Specifications</h3>
                        <p class="text-gray-700"><strong>Gender:</strong> {{$bike['gender']}}</p>
                        <p class="text-gray-700"><strong>Shifting:</strong> {{$bike['shifting']['kind']}}</p>
                        <p class="text-gray-700"><strong>Weight (KG):</strong> {{$bike['weight']['weightKG']}}</p>
                        <p class="text-gray-700"><strong>Wheels (inch):</strong> {{ implode(', ', $bike['wheels']['kinds'] ?? []) }}</p>
                        <p class="text-gray-700"><strong>Gearing Front:</strong> {{implode(', ', $bike['gearing']['front'] ?? [])}}</p>
                        <p class="text-gray-700"><strong>Gearing Rear:</strong> {{implode(', ', $bike['gearing']['rear'] ?? [])}}</p>
                        <p class="text-gray-700"><strong>Suspension:</strong> {{$bike['suspension']['configuration']}}</p>
                        <p class="text-gray-700"><strong>Frame Material:</strong> {{implode(', ', $bike['frame']['material'] ?? [])}}</p>
                    </div>

                </div>
            </div>
            
            <!-- Components Table -->
            <div class="mt-6">
                <h3 class="text-xl font-semibold mb-3">Components</h3>
                <table class="table-auto border-collapse border border-gray-300 w-full text-left">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="border border-gray-300 px-4 py-2">Component</th>
                            <th class="border border-gray-300 px-4 py-2">Description</th>
                            <th class="border border-gray-300 px-4 py-2">Display</th>
                            <th class="border border-gray-300 px-4 py-2">Maker</th>
                            <th class="border border-gray-300 px-4 py-2">Model</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bike['components'] ?? [] as $component => $details)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 font-semibold">{{ ucfirst($component) }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $details['description'] ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $details['display'] ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $details['maker'] ?? 'N/A' }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $details['model'] ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Description -->
            @if($bike['description'] ?? false)
            <div class="mt-6">
                <h3 class="text-xl font-semibold mb-2">Description</h3>
                <p class="text-gray-700">{{ $bike['description'] }}</p>
            </div>
            @endif

            <!-- Favorite Button -->
            <div class="mt-6">
                <button id="favoriteButton" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Add to Favorites
                </button>
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

        const favoriteButton = document.getElementById("favoriteButton");
        const bikeId = @json($bike['id']);
        favoriteButton.addEventListener("click", function () {
            console.log('Favorite button clicked');
            fetch('/favorite', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ bike_id: bikeId })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then(data => {
                console.log('Response:', data);
                if (data.success) {
                    alert('Bike added to favorites!');
                } else {
                    alert('Failed to add bike to favorites.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });
    </script>
</x-layout>
