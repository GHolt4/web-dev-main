<?php
 
namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
 
class SearchBikesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
 
    public function rules(): array
    {
        return [
            'year' => 'nullable|integer|min:1900|max:2025',
            'subcategory' => 'nullable|string',
            'makerId' => 'nullable|string',
            'maker' => 'nullable|string',
        ];
    }
    
}