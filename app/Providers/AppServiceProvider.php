<?php

namespace App\Providers;

use App\Events\BookingCreatedEvent;
use App\Events\BookingDeletedEvent;
use App\Events\BookingUpdatedEvent;
use App\Listeners\BookingCreatedListener;
use App\Listeners\BookingDeletedListener;
use App\Listeners\BookingUpdatedListener;
use Illuminate\Support\ServiceProvider;
use Symfony\Contracts\EventDispatcher\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        $listen = [

        BookingCreatedEvent::class => [
            BookingCreatedListener::class,
        ],
        BookingUpdatedEvent::class => [
            BookingUpdatedListener::class,
        ],
        BookingDeletedEvent::class => [
            BookingDeletedListener::class,
        ],
    ];



    }
}
