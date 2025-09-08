<?php

namespace Klunker\LaravelSubscribe;

use Klunker\LaravelSubscribe\Model\Subscriber;

class Subscribe
{

    public function getToken(string $email): string
    {
        return base64_encode($email . '|' . config('app.key'));
    }

    private function getEmail(string $token): string
    {
        return explode('|', base64_decode($token))[0];
    }


    /**
     * Get subscriber by email
     * @param string $email
     * @return ?Subscriber
     */
    public function getSubscriber(string $email): ?Subscriber
    {
        return Subscriber::query()->where('email', $email)->first();
    }

    /**
     * Get unsubscribe url
     * @param Subscriber $subscriber
     * @return string
     */
    public function getUnsubscribeUrl(Subscriber $subscriber): string
    {
        return route('api.subscribe.unsubscribe_by_token', $this->getToken($subscriber->email));
    }

    /**
     * Get subscriber by token
     * @param string $token
     * @return ?Subscriber
     */
    public function getSubscriberByToken(string $token): ?Subscriber
    {
        return Subscriber::query()->where('email', $this->getEmail($token))->first();
    }

}