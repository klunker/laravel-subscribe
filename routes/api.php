<?php

use Illuminate\Support\Facades\Route;
use Klunker\LaravelSubscribe\Http\Controllers\SubscriberController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/subscribe/view', [SubscriberController::class, 'view'])
    ->name('subscribe.view');

Route::get('/subscribe/token', [SubscriberController::class, 'token'])
    ->name('subscribe.token');

Route::post('/subscribe', [SubscriberController::class, 'store'])
    ->name('subscribe.store');

Route::patch('/unsubscribe/{token}', [SubscriberController::class, 'unsubscribe'])
    ->name('subscribe.unsubscribe_by_token')
    ->middleware('subscribed');

Route::delete('/unsubscribe/{token}', [SubscriberController::class, 'delete'])
    ->name('subscribe.unsubscribe_by_token')
    ->middleware('subscribed');

