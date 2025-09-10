<?php

namespace Klunker\LaravelSubscribe\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Klunker\LaravelSubscribe\Facades\Subscribe;
use Klunker\LaravelSubscribe\Model\Subscriber;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Subscriber $subscriber;
    public string $unsubscribeUrl;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
        $this->unsubscribeUrl = Subscribe::getUnsubscribeUrl($subscriber);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->subject(config('subscribe.welcome_email_subject'))
            ->markdown(config('subscribe.welcome_markdown_template'));
    }
}