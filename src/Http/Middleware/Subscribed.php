<?php

namespace Klunker\LaravelSubscribe\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Klunker\LaravelSubscribe\Facades\Subscribe;

class Subscribed
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $subscriber = false;

        if ($request->has('token')) {
            $subscriber = Subscribe::getSubscriberByToken($request->token);
        }

        if ($request->has('email')) {
            $subscriber = Subscribe::getSubscriber($request->email);
        }

        if (!$subscriber) {
            abort(403, 'Subscriber not found.');
        }

        return $next($request);
    }
}