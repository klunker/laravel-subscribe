<?php

namespace Klunker\LaravelSubscribe\Http\Controllers;

use Illuminate\Routing\Controller;
use Klunker\LaravelSubscribe\Events\SubscriberCreated;
use Klunker\LaravelSubscribe\Http\Requests\StoreSubscriberRequest;
use Klunker\LaravelSubscribe\Model\Subscriber;

class SubscriberController extends Controller
{
    public function store(StoreSubscriberRequest $request)
    {
        $subscriber = Subscriber::updateOrCreate(
            [
                'email' => $request->validated('email')
            ],
            $request->validated()
        );

        $wasRecentlyCreated = $subscriber->wasRecentlyCreated;
        if ($wasRecentlyCreated) {
            SubscriberCreated::dispatch($subscriber);
        }

        return response()->json([
            'message' => $wasRecentlyCreated ? 'Subscriber created' : 'Subscriber updated',
            'subscriber' => $subscriber
        ], $wasRecentlyCreated ? 201 : 200); //201 Created, 200 OK
    }

}