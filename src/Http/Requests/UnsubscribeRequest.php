<?php

namespace Klunker\LaravelSubscribe\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Klunker\LaravelSubscribe\Enums\SubscribeType;

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
            'subscribe_on' => 'sometimes|array',
            'subscribe_on.*' => ['sometimes', Rule::enum(SubscribeType::class)],
        ];
    }

    public function messages(): array
    {
        // Get all possible enum values as a comma-separated string
        $allowedTypes = implode(', ', array_column(SubscribeType::cases(), 'value'));

        return [
            'subscribe_on.*.Illuminate\\Validation\\Rules\\Enum' => 'The subscription type is invalid. Allowed types are: ' . $allowedTypes,
        ];
    }
}