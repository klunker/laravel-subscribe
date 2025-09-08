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
        $subscriber = Subscriber::withTrashed()
            ->where('email', $request->validated('email'))
            ->first();

        $wasRecentlyCreated = false;

        if ($subscriber) {
            if ($subscriber->trashed()) {
                $subscriber->restore();
            }
            $subscriber->update($request->validated());
        } else {
            $subscriber = Subscriber::create($request->validated());
            SubscriberCreated::dispatch($subscriber);
            $wasRecentlyCreated = true;
        }

        return response()->json([
            'message' => $wasRecentlyCreated ? 'Subscriber created' : 'Subscriber updated',
            'subscriber' => $subscriber
        ], $wasRecentlyCreated ? 201 : 200);
    }

    public function unsubscribe(UnsubscribeRequest $request)
    {
        $subscriber = Subscribe::getSubscriberByToken($request->validated('token'));
        $wasRecentlyUpdated = $subscriber->update($request->validated());

        if ($wasRecentlyUpdated) {
            SubscriberUpdated::dispatch($subscriber);
        }

        return response()->json([
            'message' => 'Subscriber updated',
            'subscriber' => $subscriber
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
        $email = $subscriber->email;
        $wasDeleted = $subscriber->delete();
        if ($wasDeleted) {
            SubscriberDeleted::dispatch($email);
        }
        return response()->json([
            'message' => 'Subscriber deleted',
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