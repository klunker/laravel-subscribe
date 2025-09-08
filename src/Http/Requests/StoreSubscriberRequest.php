<?php

namespace Klunker\LaravelSubscribe\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Klunker\LaravelSubscribe\Enums\SubscribeType;

class StoreSubscriberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tableName = config('subscribe.table_name', 'subscribers');
        return [
            'email' => [
                'required',
                'email',
                Rule::unique($tableName)->where(function ($query) {
                    return $query->whereNull('deleted_at');
                })
            ],
            'name' => 'sometimes|string|max:255',
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