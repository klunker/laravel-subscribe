<?php

namespace Klunker\LaravelSubscribe;

use Klunker\LaravelSubscribe\Events\SubscriberCreated;
use Klunker\LaravelSubscribe\Events\SubscriberDeleted;
use Klunker\LaravelSubscribe\Events\SubscriberUpdated;
use Klunker\LaravelSubscribe\Model\Subscriber;

class Subscribe
{

    /**
     * Get token by email
     * @param string $email
     * @return string
     */
    public function getToken(string $email): string
    {
        return base64_encode($email . '|' . config('app.key'));
    }

    /**
     * Get email by token
     * @param string $token
     * @return string
     */
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

    /**
     * Create or update subscriber
     * @param string $email
     * @param string|null $name
     * @param array $subscribeOn
     * @return Subscriber
     */
    public function subscribe(string $email, ?string $name, array $subscribeOn = []): Subscriber
    {
        $subscriber = Subscriber::withTrashed()
            ->where('email', $email)
            ->first();

        if ($subscriber) {
            if ($subscriber->trashed()) {
                $subscriber->restore();
            }
            $subscriber->update([
                'name' => $name,
                'subscribe_on' => $subscribeOn,
            ]);
            SubscriberUpdated::dispatch($subscriber);
        } else {
            $subscriber = Subscriber::create([
                'email' => $email,
                'name' => $name,
                'subscribe_on' => $subscribeOn,
            ]);
            SubscriberCreated::dispatch($subscriber);
        }

        return $subscriber;
    }

    /**
     * Update subscriber
     * @param Subscriber $subscriber
     * @param string|null $name
     * @param array $subscribeOn
     * @return Subscriber
     */
    public function updateSubscriber(Subscriber $subscriber, ?string $name, array $subscribeOn = []): Subscriber
    {
        return self::subscribe($subscriber->email, $name, $subscribeOn);
    }

    /**
     * Delete subscriber
     * @param Subscriber $subscriber
     * @return bool
     */
    public function deleteSubscriber(Subscriber $subscriber): bool
    {
        $email = $subscriber->email;
        $wasRecentlyDeleted = $subscriber->delete();
        if ($wasRecentlyDeleted) {
            SubscriberDeleted::dispatch($email);
        }
        return $wasRecentlyDeleted;
    }
}