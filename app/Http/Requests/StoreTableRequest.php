<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTableRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'seats' => 'required|numeric|min:1|max:1000',
            'hall' => 'required|exists:halls,id'
        ];
    }
}
