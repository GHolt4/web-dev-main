<x-layout>
    <x-slot:heading>
        Favourites Page
    </x-slot:heading>

    @if(isset($favouriteBikes) && count($favouriteBikes) > 0)
    <h3>Your Favourite Bikes:</h3>
    <div class="bike-list">
        @foreach($favouriteBikes as $bike)
            <div class="bike-card">
                <h4>{{ $bike->name }}</h4>
                <p>ID: {{ $bike->id }}</p>
                <p>Model: {{ $bike->model }}</p>
                <!-- Add more bike details as needed -->
            </div>
        @endforeach
    </div>
    @else
        <p>You don't have any favourite bikes yet!</p>
    @endif
</x-layout>