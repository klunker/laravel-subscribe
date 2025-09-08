<?php

use Illuminate\Support\Facades\Route;
use Klunker\LaravelSubscribe\Http\Controllers\SubscriberController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// The name is now just 'subscribe.store' because 'api.' is added by the group
Route::post('/subscribe', [SubscriberController::class, 'store'])
    ->name('subscribe.store');