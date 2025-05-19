<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', Rule::exists('users', 'email')],
            'password' => ['required', 'min:8', 'max:30']
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'A user with this email does not exist',
            'password.min' => 'Password must be at least 8 characters long',
            'password.max' => 'Password must be at most 30 characters long',
        ];
    }
}
