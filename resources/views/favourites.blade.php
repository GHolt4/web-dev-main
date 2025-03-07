<x-layout>
    <x-slot:heading>
        Favourites Page
    </x-slot:heading>
    <div id="favourites-list"></div>
    <script>
        async function loadFavourites() {
            const response = await fetch('/favourites');
            const result = await response.json();
            const favourites = result.favourites;

            let html = "";
            for (const bikeId of favourites) {
                let bikeData = await fetch(`https://api.99spokes.com/v1/bikes/${bikeId}`);
                let bike = await bikeData.json();
                
                html += `<p>${bike.name} - <button onclick="toggleFavourite('${bike.id}')">Remove</button></p>`;
            }

            document.getElementById('favourites-list').innerHTML = html;
        }

        loadFavourites();
    </script>
</x-layout>