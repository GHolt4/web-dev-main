<?php
 
namespace App\Services;
 
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
 
class UpdatedNinetyNineSpokesService
{
    protected string $baseUrl = 'https://api.99spokes.com/v1';
    protected string $apiKey;
 
    public function __construct()
    {
        $this->apiKey = config('services.99spokes.key');
        
        // Log the API key (first 3 characters for debugging, don't log full keys in production)
        Log::info('99 Spokes service initialized', [
            'api_key_prefix' => substr($this->apiKey, 0, 3) . '...',
            'api_key_length' => strlen($this->apiKey)
        ]);
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
        Log::info('Searching bikes with params:', $params);
        return $this->makeRequest('GET', '/bikes', $params);
    }
 
    /**
     * Get available bike categories
     */
    public function getCategories(): Response
    {
        return $this->makeRequest('GET', '/categories');
    }

    /**
     * Get available bike subcategories, optionally filtered by year
     */
    public function getSubcategories(string $year = null): Response
    {    
        $params = [];
        if ($year) {
            $params['year'] = $year;
        }
        $params['id'] = 'enduro';
        
        Log::info('Getting subcategories with params:', $params);
        return $this->makeRequest('GET', '/bikes/subcategories', $params);
    }
 
    /**
     * Get available bike brands
     */
    public function getBrands(): Response
    {
        return $this->makeRequest('GET', '/brands');
    }
 
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

        try {
            // Make API request with headers and updated params
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json',
            ])->$method($url, $method === 'GET' ? $params : [], $method !== 'GET' ? $params : []);
    
            Log::info('API response received', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'content_type' => $response->header('Content-Type'),
                'content_length' => $response->header('Content-Length')
            ]);
    
            // Only log body for non-200 responses to avoid logging too much
            if (!$response->successful()) {
                Log::error('API error response body:', [
                    'body' => $response->body()
                ]);
            }
            
            return $response;
        } catch (\Exception $e) {
            Log::error('Exception making API request:', [
                'message' => $e->getMessage(),
                'url' => $url,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a fake response with error details
            return new \Illuminate\Http\Client\Response(new \GuzzleHttp\Psr7\Response(
                500, 
                ['Content-Type' => 'application/json'],
                json_encode(['error' => $e->getMessage()])
            ));
        }
    }
}
