<?php

namespace Klunker\LaravelSubscribe\Http\Controllers;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Klunker\LaravelSubscribe\Model\Subscriber;

class SubscriberDeleted
{
    use Dispatchable, SerializesModels;

    public string $email;

    public function __construct(string $email)
    {
        validator(['email' => $email], ['email' => 'required|email'])->validate();
        $this->email = $email;
    }

}