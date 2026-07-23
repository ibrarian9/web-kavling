<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class ReceiptVerificationController extends Controller
{
    public function verify($id)
    {
        $booking = Booking::with(['project', 'unit', 'creator'])->findOrFail($id);

        return view('bookings.verify_public', [
            'booking' => $booking,
            'unit' => $booking->unit,
            'project' => $booking->project,
        ]);
    }
}
