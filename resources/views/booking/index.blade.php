@foreach($bookings as $booking)
    <tr>
        <td class="border px-4 py-2">{{ $booking->id }}</td>
        <td class="border px-4 py-2">{{ $booking->user->name }}</td>

        <td class="border px-4 py-2">{{ toUserDateTime($booking->start, auth()->user()) }}</td>

        <td class="border px-4 py-2">{{ toUserDateTime($booking->end, auth()->user()) }}</td>
        <td class="border px-4 py-2 text-center">

        </td>
    </tr>
@endforeach
