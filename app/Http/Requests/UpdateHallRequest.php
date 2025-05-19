<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHallRequest extends FormRequest
{
    public function rules(): array
    {
        $storeRules = (new StoreHallRequest())->rules();

        return [
            ...$storeRules,
        ];
    }

    public function messages(): array
    {
        $storeMessages = (new StoreHallRequest())->messages();

        return [
            ...$storeMessages,
        ];
    }
}
