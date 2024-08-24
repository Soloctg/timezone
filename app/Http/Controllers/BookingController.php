<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
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
        $request->user()->bookings()->create([
           // 'start' => $request->validated('start'),
            'start' => fromUserDateTime($request->validated('start')),
            //'end' => $request->validated('end'),
            'end' => fromUserDateTime($request->validated('end')),
        ]);


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

        return redirect()->route('booking.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 404);

        $booking->delete();

        return redirect()->route('booking.index');
    }
}
