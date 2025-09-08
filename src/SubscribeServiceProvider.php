<?php

namespace Klunker\LaravelSubscribe;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Klunker\LaravelSubscribe\Events\SubscriberCreated;
use Klunker\LaravelSubscribe\Listeners\SendWelcomeEmail;

class SubscribeServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the package.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        SubscriberCreated::class => [
            SendWelcomeEmail::class,
        ],
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/subscribe.php', 'subscribe'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the package routes with the corrected path
        $this->bootRoutes();
        
        // Register the package migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Register the package views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'subscribe');

        // Register event listeners
        $this->registerEventListeners();

        // Register files for publishing
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/subscribe.php' => config_path('subscribe.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/subscribe'),
            ], 'views');
        }
    }

    /**
     * Boot the package's routes.
     */
    protected function bootRoutes(): void // 3. Create a dedicated method for routes
    {
        Route::prefix('api')
            ->middleware('api')
            ->name('api.')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
            });
    }

    /**
     * Register the package's event listeners.
     */
    protected function registerEventListeners(): void
    {
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}