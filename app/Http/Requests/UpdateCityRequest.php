<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        // Отримуємо ID міста з URL (наприклад, /admin/cities/{id})
        $id = $this->route('id') ?? $this->route('city');

        return [
            'city_name' => 'required|string|max:255|unique:cities,city_name,' . $id
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