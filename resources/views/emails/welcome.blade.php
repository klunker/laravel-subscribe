@component('mail::message')
    # Welcome, {{ $subscriber->name ?? 'friend' }}!

    Thank you for subscribing to our newsletter. We're excited to have you on board.

    You'll be the first to know about new updates, articles, and special offers.

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent