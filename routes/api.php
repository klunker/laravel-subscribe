<?php

use Illuminate\Support\Facades\Route;
use Klunker\LaravelSubscribe\Http\Controllers\SubscriberController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::get('/subscribe/channels', [SubscriberController::class, 'channels'])
    ->name('subscribe.channels');

Route::get('/subscribe/view', [SubscriberController::class, 'view'])
    ->name('subscribe.view');

Route::get('/subscribe/token', [SubscriberController::class, 'token'])
    ->name('subscribe.token');

Route::post('/subscribe', [SubscriberController::class, 'store'])
    ->name('subscribe.store');

Route::patch('/unsubscribe', [SubscriberController::class, 'unsubscribe'])
    ->name('subscribe.unsubscribe_by_token')
    ->middleware('subscribed');

Route::delete('/unsubscribe', [SubscriberController::class, 'delete'])
    ->name('subscribe.delete_by_token')
    ->middleware('subscribed');

