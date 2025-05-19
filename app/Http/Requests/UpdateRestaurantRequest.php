<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRestaurantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255|required_without_all:address,phone',
            'address' => 'nullable|string|max:255|required_without_all:name,phone',
            'phone' => 'nullable|string|max:20|required_without_all:name,address',
        ];
    }
}
