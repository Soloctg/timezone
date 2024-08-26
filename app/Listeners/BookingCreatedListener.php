<?php

namespace App\Listeners;

use App\Events\BookingCreatedEvent;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BookingCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingCreatedEvent $event): void
    {
        $booking = $event->booking;
        $booking->load('user');
        $startTime = CarbonImmutable::parse(toUserDateTime($booking->start, $booking->user), $booking->user->timezone);

        $booking->createReminderNotifications($booking, $startTime);
    }
}
