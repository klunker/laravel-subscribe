<?php

namespace Klunker\LaravelSubscribe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Klunker\LaravelSubscribe\Http\Requests\UnsubscribePageRequest;
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
                'result' => true,
                'subscriber' => SubscriberResource::make($subscriber)
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'result' => false,
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
                'result' => true,
                'message' => $wasRecentlyCreated ? 'Subscriber created' : 'Subscriber updated',
                'subscriber' => SubscriberResource::make($subscriber->refresh())
            ], $wasRecentlyCreated ? 201 : 200);
        } catch (Throwable $e) {
            return response()->json([
                'result' => false,
                'message' => 'Error while processing request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function unsubscribe_page(UnsubscribePageRequest $request)
    {
        $subscriber = Subscribe::getSubscriberByToken($request->validated('token'));

        return view('subscribe::unsubscribe_page', [
            'subscriber' => $subscriber
        ]);
    }

    public function update(UnsubscribeRequest $request)
    {
        Log::info('Subscriber updated', $request->validated());
        try {
            $subscriber = Subscribe::getSubscriberByToken($request->validated('token'));
            $subscriber = Subscribe::updateSubscriber(
                $subscriber,
                $request->validated('name'),
                $request->validated('subscribe_on')
            );

            return response()->json([
                'result' => true,
                'message' => 'Subscriber updated',
                'subscriber' => SubscriberResource::make($subscriber->refresh())
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'result' => false,
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
                'result' => true,
                'is_deleted' => $isDeleted,
                'message' => $isDeleted
                    ? 'Subscriber deleted'
                    : 'Subscriber not deleted',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'result' => false,
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
                'result' => true,
                'token' => $token
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'result' => false,
                'message' => 'Error while processing request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function channels()
    {
        try {
            return response()->json([
                'result' => true,
                'channels' => Subscribe::getChannels()
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'result' => false,
                'message' => 'Error while processing request',
                'error' => $e->getMessage()
            ], 500);
        }

    }

}
