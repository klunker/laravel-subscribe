<?php

namespace Klunker\LaravelSubscribe\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Klunker\LaravelSubscribe\Model\Subscriber;

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
            'email' => 'required|email|unique:' . $tableName . ',email',
            'name' => 'sometimes|string|max:255',
            'service' => 'sometimes|boolean',
            'marketing' => 'sometimes|boolean',
        ];
    }

}