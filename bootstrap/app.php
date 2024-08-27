<?php

use App\Events\BookingCreatedEvent;
use App\Events\BookingDeletedEvent;
use App\Events\BookingUpdatedEvent;
use App\Listeners\BookingCreatedListener;
use App\Listeners\BookingDeletedListener;
use App\Listeners\BookingUpdatedListener;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'setTimezone' => \App\Http\Middleware\SetTimezoneMiddleware::class,
            //$schedule->command('send:scheduled-notifications')->everyMinute(),
        ]);

    

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
