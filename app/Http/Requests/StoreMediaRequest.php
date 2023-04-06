<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
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
            'image' => 'required|image|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'title.nullable' => 'Title must be a string.',
            'image.required' => 'Image is required.'       
        ];
    }
}
