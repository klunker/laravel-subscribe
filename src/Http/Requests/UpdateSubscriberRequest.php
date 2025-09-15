<?php

namespace Klunker\LaravelSubscribe\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Klunker\LaravelSubscribe\Enums\SubscribeType;
use Klunker\LaravelSubscribe\Rules\ValidSubscribeBroadcastChannel;

class UpdateSubscriberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required',
            'subscribe_on' => 'required|array',
            'subscribe_on.*' => ['required', new ValidSubscribeBroadcastChannel()],
        ];
    }
}
