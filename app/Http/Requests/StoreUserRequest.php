<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'profile.first_name' => 'required|string',
            'profile.last_name' => 'required|string',
            'profile.email' => 'required|email|unique:users,email',
            'profile.phone' => 'required|string',
            'profile.phone_ext' => 'string',
            'profile.mobile' => 'string',
            'profile.contact_preference' => 'string',
            'dealership.name' => 'required|string',
            'dealership.street_name' => 'required|string',
            'dealership.city' => 'required|string',
            'dealership.state' => 'required|string',
            'dealership.zip_code' => 'required|string',
            'dealership.car_stock' => 'required|string'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'profile.first_name.required' =>'First name is required!',
            'profile.last_name.required' =>'Last name is required!',
            'profile.email.required' => 'Email is required!',
            'profile.phone.required' => 'Phone is required!',
            'dealership.name.required' => 'Dealership name is required!',
            'dealership.street_name.required' => 'Dealership street name is required!',
            'dealership.city.required' => 'City is required!',
            'dealership.state.required' => 'State is required!',
            'dealership.zip_code.required' => 'Zip code is required!',
            'dealership.car_stock.required' => 'Car stock is required!'
        ];
    }
}
