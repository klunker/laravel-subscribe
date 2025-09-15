@component('mail::message')
    # Welcome, {{ $subscriber->name ?? 'friend' }}!

    Thank you for subscribing to our newsletter. We're excited to have you on board.

    You'll be the first to know about new updates, articles, and special offers.

    Thanks,
    {{ config('app.name') }}


    @slot('subcopy')
        If you no longer wish to receive these emails, you can
        [unsubscribe here]({{ $unsubscribe_token }}).
    @endslot
@endcomponent
