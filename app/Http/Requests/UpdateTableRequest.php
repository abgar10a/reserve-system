<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTableRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255|required_without_all:seats,hall',
            'seats' => 'nullable|numeric|min:1|max:1000|required_without_all:name,hall',
            'hall' => 'nullable|exists:halls,id|required_without_all:name,seats'
        ];
    }
}
