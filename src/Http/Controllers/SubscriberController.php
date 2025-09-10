<?php

namespace Klunker\LaravelSubscribe\Http\Controllers;

use Klunker\LaravelSubscribe\Http\Resources\SubscriberResource;
use Throwable;
use Illuminate\Routing\Controller;
use Klunker\LaravelSubscribe\Facades\Subscribe;
use Klunker\LaravelSubscribe\Http\Requests\DeleteRequest;
use Klunker\LaravelSubscribe\Http\Requests\StoreSubscriberRequest;
use Klunker\LaravelSubscribe\Http\Requests\UnsubscribeRequest;
use Klunker\LaravelSubscribe\Http\Requests\ViewSubscriberRequest;

class SubscriberController extends Controller
{
    public function view(ViewSubscriberRequest $request)
    {
        try {
            $subscriber = Subscribe::getSubscriber($request->validated('email'));
            return response()->json([
                'subscriber' => SubscriberResource::make($subscriber)
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error while processing request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreSubscriberRequest $request)
    {
        try {
            $subscriber = Subscribe::subscribe(
                $request->validated('email'),
                $request->validated('name'),
                $request->validated('subscribe_on')
            );

            $wasRecentlyCreated = $subscriber->wasRecentlyCreated;

            return response()->json([
                'message' => $wasRecentlyCreated ? 'Subscriber created' : 'Subscriber updated',
                'subscriber' => SubscriberResource::make($subscriber->refresh())
            ], $wasRecentlyCreated ? 201 : 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error while processing request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function unsubscribe(UnsubscribeRequest $request)
    {
        try {
            $subscriber = Subscribe::getSubscriberByToken($request->validated('token'));
            $subscriber = Subscribe::updateSubscriber(
                $subscriber,
                $request->validated('name'),
                $request->validated('subscribe_on')
            );

            return response()->json([
                'message' => 'Subscriber updated',
                'subscriber' => SubscriberResource::make($subscriber->refresh())
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error while processing request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(DeleteRequest $request)
    {
        try {
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
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error while processing request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function token(ViewSubscriberRequest $request)
    {
        try {
            $token = Subscribe::getToken($request->validated('email'));
            return response()->json([
                'token' => $token
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error while processing request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function channels()
    {
        try {
            return response()->json([
                'channels' => Subscribe::getChannels()
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error while processing request',
                'error' => $e->getMessage()
            ], 500);
        }

    }

}