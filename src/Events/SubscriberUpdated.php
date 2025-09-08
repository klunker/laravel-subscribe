<?php

namespace Klunker\LaravelSubscribe\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Klunker\LaravelSubscribe\Model\Subscriber;

class SubscriberUpdated
{
    use Dispatchable, SerializesModels;

    public Subscriber $subscriber;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

}