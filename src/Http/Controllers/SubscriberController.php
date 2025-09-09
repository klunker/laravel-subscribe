<?php

namespace Klunker\LaravelSubscribe\Http\Controllers;

use Illuminate\Routing\Controller;
use Klunker\LaravelSubscribe\Events\SubscriberCreated;
use Klunker\LaravelSubscribe\Events\SubscriberDeleted;
use Klunker\LaravelSubscribe\Events\SubscriberUpdated;
use Klunker\LaravelSubscribe\Facades\Subscribe;
use Klunker\LaravelSubscribe\Http\Requests\DeleteRequest;
use Klunker\LaravelSubscribe\Http\Requests\StoreSubscriberRequest;
use Klunker\LaravelSubscribe\Http\Requests\UnsubscribeRequest;
use Klunker\LaravelSubscribe\Http\Requests\ViewSubscriberRequest;
use Klunker\LaravelSubscribe\Model\Subscriber;

class SubscriberController extends Controller
{
    public function view(ViewSubscriberRequest $request)
    {
        $subscriber = Subscribe::getSubscriber($request->validated('email'));
        return response()->json([
            'subscriber' => $subscriber
        ]);
    }

    public function store(StoreSubscriberRequest $request)
    {
        $subscriber = Subscribe::subscribe(
            $request->validated('email'),
            $request->validated('name'),
            $request->validated('subscribe_on')
        );

        $wasRecentlyCreated = $subscriber->wasRecentlyCreated;

        return response()->json([
            'message' => $wasRecentlyCreated ? 'Subscriber created' : 'Subscriber updated',
            'subscriber' => $subscriber->refresh()
        ], $wasRecentlyCreated ? 201 : 200);
    }

    public function unsubscribe(UnsubscribeRequest $request)
    {
        $subscriber = Subscribe::getSubscriberByToken($request->validated('token'));
        $subscriber = Subscribe::updateSubscriber(
            $subscriber,
            $request->validated('name'),
            $request->validated('subscribe_on')
        );

        return response()->json([
            'message' => 'Subscriber updated',
            'subscriber' => $subscriber->refresh()
        ]);
    }

    public function delete(DeleteRequest $request)
    {
        $subscriber = Subscribe::getSubscriberByToken($request->validated('token'));
        if (!$subscriber) {
            return response()->json([
                'message' => 'Subscriber not found',
            ], 404);
        }
        $isDeleted = Subscribe::deleteSubscriber($subscriber);
        return response()->json([
            'message' => $isDeleted
                ? 'Subscriber deleted'
                : 'Subscriber not deleted',
        ]);

    }

    public function token(ViewSubscriberRequest $request)
    {
        $token = Subscribe::getToken($request->validated('email'));
        return response()->json([
            'token' => $token
        ]);
    }

}