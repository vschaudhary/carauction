<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'type' => 'integer|required',
            'reserve_amount' => 'nullable|decimal:2|required_if:type,2,3',
            'started_at' => 'date|nullable|required_if:type,2',
            'completed_at' => 'date|nullable',
            'status' => 'integer|nullable',
            'vehicle_id' => 'integer|required|exists:vehicles,id',
            'seller_id' => 'integer|required',
            'type_id' => 'integer|nullable',
            'created_by_id' => 'integer|nullable',
        ];
    }

    /**
     * Custome message for rule
     */
    public function messages()
    {
        return [
            'type.integer' => 'Type is not valid.',
            'type.required' => 'Type is required',
            'started_at.date' => 'Start date is not a valid date.',
            'completed_at.date' => 'Completed date is not a valid date',
            'status.integer' => 'Status is not valid.',
            'vehicle_id.integer' => 'vehicle_id must be integer.',
            'seller_id.integer' => 'seller_id must be integer.',
            'type_id.integer' => 'type_id must be integer.',
            'created_by_id.integer' => 'created_by_id must be integer.',
            'vehicle_id.required' => 'Vehicle ID is required.',
            'seller_id.required' => 'Seller ID is required.',
            'vehicle_id.exists' => 'Vehicle does not exists.'
        ];
    }
}
