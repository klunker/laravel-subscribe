<?php

namespace Klunker\LaravelSubscribe\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteSubscriberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required',
        ];
    }
}
