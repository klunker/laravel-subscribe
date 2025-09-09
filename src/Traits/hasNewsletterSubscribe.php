<?php

namespace Klunker\LaravelSubscribe\Traits;

use Klunker\LaravelSubscribe\Enums\SubscribeType;
use Klunker\LaravelSubscribe\Facades\Subscribe as SubscriptionManager;
use Klunker\LaravelSubscribe\Model\Subscriber;

trait hasNewsletterSubscribe
{
    /**
     * Get the subscriber record associated with the user.
     */
    public function subscriber()
    {
        return $this->hasOne(Subscriber::class, 'email', 'email');
    }

    /**
     * Subscribe the user to the given types.
     *
     * @param array<SubscribeType> $types
     * @return Subscriber
     */
    public function subscribeTo(array $types): Subscriber
    {
        $allowedKeys = array_keys(config('subscribe.allowed_types', []));
        $filteredTypes = array_intersect($types, $allowedKeys);
        return SubscriptionManager::subscribe($this->email, $this->name, $filteredTypes);
    }

    /**
     * Unsubscribe the user from all types.
     *
     * @return bool|null
     */
    public function unsubscribeFromAll()
    {
        return $this->subscriber?->delete();
    }

    /**
     * @param SubscribeType $type
     * @return bool
     */
    public function isSubscribedTo(string $type): bool
    {
        return $this->subscriber && $this->subscriber->isSubscribedTo($type);
    }
}