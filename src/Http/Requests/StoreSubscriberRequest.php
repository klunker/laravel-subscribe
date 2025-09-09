<?php

namespace Klunker\LaravelSubscribe\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Klunker\LaravelSubscribe\Rules\ValidSubscribeBroadcastChannel;

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
            'subscribe_on' => 'required|array',
            'subscribe_on.*' => ['required', new ValidSubscribeBroadcastChannel()],
        ];
    }
}