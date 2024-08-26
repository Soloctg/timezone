<?php

namespace App\Http\Controllers;

use App\Events\BookingCreatedEvent;
use App\Events\BookingDeletedEvent;
use App\Events\BookingUpdatedEvent;
use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\ScheduledNotification;
use App\Notifications\BookingReminder1H;
use App\Notifications\BookingReminder2H;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

        //$startTime = CarbonImmutable::parse(toUserDateTime($booking->start, $booking->user), $booking->user->timezone);

        event(new BookingCreatedEvent($booking));

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
        $booking->update($request->validated());

        $booking->update([
            //'start' => $request->validated('start'),
            'start' => fromUserDateTime($request->validated('start')),
            //'end' => $request->validated('end'),
            'end' => fromUserDateTime($request->validated('end')),
        ]);

        event(new BookingUpdatedEvent($booking));

        return redirect()->route('booking.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 404);

        $booking->delete();

        event(new BookingDeletedEvent($booking));

        return redirect()->route('booking.index');
    }
}
