<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'entity_type' => 'required|string|in:table,hall',
            'entity_id' => 'required|numeric'
        ];
    }

    public function messages(): array
    {
        return [
            'entity_type.in' => 'Entity type must be table or hall',
            'end.after' => 'End date must be after start date'
        ];
    }
}
