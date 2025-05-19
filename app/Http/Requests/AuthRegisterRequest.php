<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        $loginRules = (new AuthLoginRequest())->rules();

        return [
            ...$loginRules,
            'name' => ['required', 'min:3', 'max:10']
        ];
    }

    public function messages(): array
    {
        $loginMessages = (new AuthLoginRequest())->messages();

        return [
            ...$loginMessages,
            'email.unique' => 'A user with this email already exists'
        ];
    }
}
