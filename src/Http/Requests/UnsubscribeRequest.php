<?php

namespace Klunker\LaravelSubscribe\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnsubscribeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required',
            'service' => 'sometimes|boolean',
            'marketing' => 'sometimes|boolean',
        ];
    }
}