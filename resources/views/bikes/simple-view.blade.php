{{-- resources/views/bikes/simple.blade.php --}}
<x-layout>
    <x-slot:heading>
        Simple Bike Subcategories Demo
    </x-slot:heading>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Bike Subcategories for</h2>
            
            @if(isset($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <p class="font-bold">Error:</p>
                    <p>{{ $error }}</p>
                </div>
            @endif
            
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mb-4">
                <p class="font-bold">API Status Code: {{ $apiStatus ?? 'Unknown' }}</p>
                <p>{{ isset($subcategories) && is_array($subcategories) ? count($subcategories) : 0 }} subcategories found</p>
            </div>
            
            @if(isset($subcategories) && is_array($subcategories) && count($subcategories) > 0)
                <div class="overflow-hidden border border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($subcategories as $subcategory)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subcategory['id'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $subcategory['name'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subcategory['category'] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                    <p>No subcategories found. See the raw API response below for details.</p>
                </div>
            @endif
            
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-2">Raw API Response</h3>
                <div class="bg-gray-100 p-4 rounded-lg overflow-auto max-h-96">
                    <pre>{{ $rawResponse ?? 'No response data available' }}</pre>
                </div>
            </div>
        </div>
    </div>
</x-layout>
