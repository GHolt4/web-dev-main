<?php
 
namespace App\Http\Controllers;
 
use App\Services\NinetyNineSpokesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
 
class SimpleBikesController extends Controller
{
    /**
     * Display a simple page with subcategories
     */
    public function index(NinetyNineSpokesService $service)
    {
        try {
            // Get current year
            $year = date('Y');
            
            // Initialize variables
            $subcategories = [];
            $apiStatus = null;
            $rawResponse = null;
            
            // Fetch subcategories
            $response = $service->getSubcategories($year);
            
            if ($response) {
                // Log the response for debugging
                Log::info('API Response in SimpleBikesController:', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
                
                // Get status
                $apiStatus = $response->status();
                
                // Get raw response for display
                $responseData = $response->json();
                $rawResponse = json_encode($responseData, JSON_PRETTY_PRINT);
                
                // Get subcategories from response
                if ($response->successful()) {
                    $data = $responseData;
                    
                    // Check different possible structures
                    if (isset($data['subcategories']) && is_array($data['subcategories'])) {
                        $subcategories = $data['subcategories'];
                    } elseif (is_array($data) && !empty($data)) {
                        $subcategories = $data; // Assume it's the direct array
                    }
                    
                    // Log what we found
                    Log::info('Extracted subcategories:', [
                        'count' => is_array($subcategories) ? count($subcategories) : 'not an array'
                    ]);
                } else {
                    Log::error('Failed to get subcategories', [
                        'status' => $response->status(),
                        'body' => $response->json()
                    ]);
                }
            } else {
                Log::error('No response from service');
            }
            
            return view('bikes.simple', [
                'subcategories' => $subcategories,
                'year' => $year,
                'apiStatus' => $apiStatus,
                'rawResponse' => $rawResponse
            ]);
        } catch (\Exception $e) {
            Log::error('Exception in SimpleBikesController:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('bikes.simple', [
                'error' => $e->getMessage(),
                'subcategories' => [],
                'year' => date('Y'),
                'apiStatus' => 500,
                'rawResponse' => json_encode(['error' => $e->getMessage()])
            ]);
        }
    }
}