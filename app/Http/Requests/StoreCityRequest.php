<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'city_name' => 'required|string|max:255|unique:cities,city_name'
        ];
    }

    public function messages(): array
    {
        return [
            'city_name.required' => 'Введіть назву міста!',
            'city_name.unique' => 'Це місто вже додано до системи!',
        ];
    }
}