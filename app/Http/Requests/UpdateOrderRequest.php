<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(OrderStatus::all())]
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Status must be one of: ' . implode(', ', OrderStatus::all()) . '.'
        ];
    }
}
