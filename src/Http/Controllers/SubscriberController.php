<?php

namespace Klunker\LaravelSubscribe\Http\Controllers;

use Illuminate\Routing\Controller;
use Klunker\LaravelSubscribe\Events\SubscriberCreated;
use Klunker\LaravelSubscribe\Facades\Subscribe;
use Klunker\LaravelSubscribe\Http\Requests\StoreSubscriberRequest;
use Klunker\LaravelSubscribe\Http\Requests\UnsubscribeRequest;
use Klunker\LaravelSubscribe\Http\Requests\ViewSubscriberRequest;
use Klunker\LaravelSubscribe\Model\Subscriber;

class SubscriberController extends Controller
{
    public function view(ViewSubscriberRequest $request)
    {
        $subscriber = Subscribe::getSubscriber($request->validated('email'));
        $wasRecentlyCreated = false;

        if ($subscriber) {
            if ($subscriber->trashed()) {
                $subscriber->restore();
            }

            $subscriber->update($request->validated());

        } else {
            $subscriber = Subscriber::create($request->validated('email'));
            $wasRecentlyCreated = true;
        }

        if ($wasRecentlyCreated) {
            SubscriberCreated::dispatch($subscriber);
        }

        return response()->json([
            'message' => $wasRecentlyCreated
                ? 'Successfully subscribed'
                : 'Successfully has been updated',
            'subscriber' => $subscriber->refresh()
        ], $wasRecentlyCreated ? 201 : 200);
    }

    public function store(StoreSubscriberRequest $request)
    {

        $subscriber = Subscriber::withTrashed()
            ->where('email', $request->validated('email'))
            ->first();


        $wasRecentlyCreated = $subscriber->wasRecentlyCreated;
        if ($wasRecentlyCreated) {
            SubscriberCreated::dispatch($subscriber);
        }

        return response()->json([
            'message' => $wasRecentlyCreated ? 'Subscriber created' : 'Subscriber updated',
            'subscriber' => $subscriber
        ], $wasRecentlyCreated ? 201 : 200); //201 Created, 200 OK
    }

    public function unsubscribe(UnsubscribeRequest $request)
    {
        $subscriber = Subscribe::getSubscriberByToken($request->validated('token'));
        $wasRecentlyUpdated = $subscriber->update([
            'service' => $request->validated('service'),
            'marketing' => $request->validated('marketing'),
        ]);
        if ($wasRecentlyUpdated) {
            SubscriberUpdated::dispatch($subscriber);
        }

        return response()->json([
            'message' => 'Subscriber updated',
            'subscriber' => $subscriber
        ]);
    }

    public function delete(UnsubscribeRequest $request)
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