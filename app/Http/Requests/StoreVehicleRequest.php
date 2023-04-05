<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'nullable|string',
            'model_name' => 'required|string',
            'model_year' => 'required|integer|digits:4',
            'make' => 'required|string',
            'body_type' => 'required|string',
            'distance_covered' => 'required|integer',
            'location' => 'string|nullable',
            'amount' => 'decimal:2|nullable',
            'description' => 'string|nullable',
            'status' => 'required|integer',
            'user_id' => 'required|integer',
            'type_id' => 'nullable|integer',
            'created_by_id' => 'nullable|integer',
        ];
    }

    /**
     * Custome message for validation
     * 
     * @return array
     */
    public function messages(){
        return [
            'title.string' => 'Title must be a string.',
            'model_name.required' => 'Model name is required.',
            'model_year.required' => 'Model year is required.',
            'make.required' => 'Make is required.',
            'body_type.required' => 'Vehicle body type is required.',
            'distance_covered.required' => 'Distance covered in KM is required.',
            'location.string' => 'Location is required.',
            'amount.decimal' => 'Amount is not valid.',
            'description.string' => 'Desctiption must be a string.',
            'status.required' => 'Status is required.',
            'user_id.required' => 'Seller ID is required.',
            'type_id.integer' => 'Invalid type id.',
            'created_by_id.integer' => 'Created By must be a integer.',            
            'model_name.string' => 'Model name must be a string.',
            'model_year.integer' => 'Model year must be a valid year.',
            'make.string' => 'Make must be a string.',
            'body_type.string' => 'Vehicle body type must be a string.',
            'distance_covered.integer' => 'Distance covered must be a string.',
            'status.integer' => 'Status must be a integer.',
            'user_id.integer' => 'Enter a valid user ID.',
            
        ];
    }
}
