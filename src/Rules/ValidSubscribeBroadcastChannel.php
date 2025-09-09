<?php

namespace Klunker\LaravelSubscribe\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidSubscribeBroadcastChannel implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        // Check if the given value exists as a KEY in our allowed types array.
        return array_key_exists($value, config('subscribe.allowed_channels', []));
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        // Get all allowed keys to show in the error message.
        $allowedKeys = array_keys(config('subscribe.allowed_channels', []));
        $allowedTypesString = implode(', ', $allowedKeys);
        return 'The subscribe broadcast  channel is invalid. Allowed types are: ' . $allowedTypesString;
    }
}
