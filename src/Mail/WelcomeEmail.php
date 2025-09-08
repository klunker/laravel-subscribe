<?php

namespace Klunker\LaravelSubscribe\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Klunker\LaravelSubscribe\Model\Subscriber;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Subscriber $subscriber;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->subject('Welcome to our Newsletter!')
            ->markdown('subscribe::emails.welcome');
    }
}