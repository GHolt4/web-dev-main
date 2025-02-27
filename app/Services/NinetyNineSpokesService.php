<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Client\Response;

class NinetyNineSpokesService

{

    protected string $baseUrl = 'https://api.99spokes.com/v1';

    protected string $apiKey;

    public function __construct()

    {

        $this->apiKey = config('services.99spokes.key');

    }

    /**

     * Get bike details by ID

     */

    public function getBike(string $bikeId): Response

    {

        return $this->makeRequest('GET', "/bikes/{$bikeId}");

    }

    /**

     * Search for bikes with given parameters

     */

    public function searchBikes(array $params): Response

    {

        $params['limit'] = 100;

        Log::info('Searching bikes with params:', $params);

        return $this->makeRequest('GET', '/bikes', $params);

    }

    /**

     * Get available bike categories

     */

    // public function getCategories(): Response

    // {

    //     return $this->makeRequest('GET', '/categories');

    // }
 
    /**

     * Get available bike subcategories, optionally filtered by year

     */

    public function getSubcategories(string $year = null): Response

    {    

        $params = [];

        if ($year) {

            $params['year'] = $year;

        }
        
        $params['id'] = 'e-1234';

        return $this->makeRequest('GET', '/bikes/subcategories', $params);

    }

    /**

     * Get available bike brands

     */

    // public function getBrands(): Response

    // {

    //     return $this->makeRequest('GET', '/brands');

    // }



    /**

     * Make an API request to 99 Spokes

     */

    protected function makeRequest(string $method, string $endpoint, array $params = []): Response

    {

        $url = $this->baseUrl . $endpoint;
 
        // Ensure 'include' is set to '*' in the query params

        $params['include'] = '*';
 
        Log::info('Making API request', [

            'method' => $method,

            'url' => $url,

            'params' => $params

        ]);
 
        // Make API request with headers and updated params

        $response = Http::withHeaders([

            'Authorization' => "Bearer {$this->apiKey}",

            'Accept' => 'application/json',

        ])->$method($url, $method === 'GET' ? $params : [], $method !== 'GET' ? $params : []);
 
        Log::info('API response', [

            'status' => $response->status(),

            'body' => $response->json(),

        ]);
 
        return $response;

    }

}