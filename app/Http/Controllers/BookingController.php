<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\ScheduledNotification;
use App\Notifications\BookingReminder1H;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::query()
            ->with(['user'])
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bookings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        //$request->user()->bookings()->create($request->validated());
        //$request->user()->bookings()->create([
        //    'start' => fromUserDateTime($request->validated('start')),
        //    'end' => fromUserDateTime($request->validated('end')),
        //]);

        $booking = $request->user()->bookings()->create([
            'start' => fromUserDateTime($request->validated('start'), $request->user()),
            'end' => fromUserDateTime($request->validated('end'), $request->user()),
        ]);

        $startTime = CarbonImmutable::parse(toUserDateTime($booking->start, $booking->user), $booking->user->timezone);

        // Schedule 1H reminder
        $oneHourTime = fromUserDateTime($startTime->subHour(), $booking->user);
        if (now('UTC')->lessThan($oneHourTime)) {
            $booking->user->scheduledNotifications()->create([
                'notification_class' => BookingReminder1H::class,
                'notifiable_id' => $booking->id,
                'notifiable_type' => Booking::class,
                'sent' => false,
                'processing' => false,
                'scheduled_at' => $oneHourTime,
                'sent_at' => null,
                'tries' => 0,
            ]);
        }

        return redirect()->route('booking.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        return view('bookings.edit', compact('booking'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        //$booking->update($request->validated());

        $booking->update([
            //'start' => $request->validated('start'),
            'start' => fromUserDateTime($request->validated('start')),
            //'end' => $request->validated('end'),
            'end' => fromUserDateTime($request->validated('end')),
        ]);

        $startTime = CarbonImmutable::parse(toUserDateTime($booking->start, $booking->user), $booking->user->timezone);

        $hasScheduledNotifications = ScheduledNotification::query()
            ->where('notifiable_id', $booking->id)
            ->where('notifiable_type', Booking::class)
            ->where('user_id', $booking->user_id)
            ->exists();

        // First we need to check if there are any already scheduled notifications
        if ($hasScheduledNotifications) {
            // Then in this example, we simply delete them. You can however update them if you want.
            $booking->scheduledNotifications()
                ->where('user_id', $booking->user_id)
                ->delete();
        }

        // Since we are clearing the scheduled notifications, we need to create them again for the new date
        // Schedule 1H reminder
        $oneHourTime = fromUserDateTime($startTime->subHour(), $booking->user);
        if (now('UTC')->lessThan($oneHourTime)) {
            $booking->user->scheduledNotifications()->create([
                'notification_class' => BookingReminder1H::class,
                'notifiable_id' => $booking->id,
                'notifiable_type' => Booking::class,
                'sent' => false,
                'processing' => false,
                'scheduled_at' => $oneHourTime,
                'sent_at' => null,
                'tries' => 0,
            ]);
        }

        return redirect()->route('booking.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 404);

        $booking->delete();

        $booking->scheduledNotifications()
            ->where('user_id', $booking->user_id)
            ->delete();

        return redirect()->route('booking.index');
    }
}
