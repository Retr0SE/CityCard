<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketPriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        $id = $this->route('id') ?? $this->route('price');

        return [
            'city_id' => 'required|exists:cities,id',
            'transport_type_id' => 'required|exists:transport_types,id',
            'price' => 'required|numeric|min:0',
            
            'ticket_type_id' => [
                'required',
                'exists:ticket_types,id',
                Rule::unique('ticket_prices')->where(function ($query) {
                    return $query->where('city_id', $this->city_id)
                                 ->where('transport_type_id', $this->transport_type_id);
                })->ignore($id)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_type_id.unique' => 'Такий тариф для цього міста та транспорту вже існує!',
            'price.required' => 'Ціна є обов\'язковою!',
        ];
    }
}