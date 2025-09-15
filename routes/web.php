<?php

use Klunker\LaravelSubscribe\Http\Controllers\SubscriberController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/unsubscribe', [SubscriberController::class, 'unsubscribe_page'])
    ->name('subscribe.unsubscribe');
