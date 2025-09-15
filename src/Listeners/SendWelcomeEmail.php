<?php

namespace Klunker\LaravelSubscribe\Listeners;

use Illuminate\Support\Facades\Mail;
use Klunker\LaravelSubscribe\Events\SubscriberCreated;
use Klunker\LaravelSubscribe\Mail\WelcomeEmail;

class SendWelcomeEmail
{
    /**
     * Handle the event.
     *
     * @param SubscriberCreated $event
     * @return void
     */
    public function handle(SubscriberCreated $event)
    {
        // For now, we'll just log that the event was caught.
        // We will add the mail sending logic after creating the Mailable.
        logger('New subscriber registered, should send welcome email to: ' . $event->subscriber->email);

        // The actual mail sending logic will look like this:
        logger('Current config subscribe.send_welcome_email value: ' . config('subscribe.send_welcome_email', false));
        if (config('subscribe.send_welcome_email', false)) {
            Mail::to($event->subscriber->email)->send(new WelcomeEmail($event->subscriber));
        }

    }
}
