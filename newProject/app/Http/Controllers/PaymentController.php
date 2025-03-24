<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\OrderDetail;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function showPaymentForm($booking_id, $cycle)
    {
        $booking = Booking::findOrFail($booking_id);

        $orderDetails = OrderDetail::where('booking_id', $booking_id)
            ->where('booking_cycle', $cycle)
            ->get();

        $total = $orderDetails->sum('total');

        return view('payment.form', compact('booking', 'cycle', 'orderDetails', 'total'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,booking_id',
            'booking_cycle' => 'required|in:1,2',
            'payment_method' => 'required|string',
        ]);

        $booking_id = $request->booking_id;
        $cycle = $request->booking_cycle;

        // รวมยอดจาก OrderDetail ตามรอบ
        $total = OrderDetail::where('booking_id', $booking_id)
            ->where('booking_cycle', $cycle)
            ->sum('total');

        // ตรวจสอบซ้ำว่าเคยจ่ายรอบนี้แล้วหรือยัง
        $existing = Payment::where('booking_id', $booking_id)
            ->where('booking_cycle', $cycle)
            ->first();

        if ($existing) {
            return back()->withErrors(['error' => 'ชำระเงินรอบนี้ไปแล้ว']);
        }

        Payment::create([
            'payment_method' => $request->payment_method,
            'total' => $total,
            'status' => 'paid',
            'booking_cycle' => $cycle,
            'booking_id' => $booking_id,
        ]);

        return redirect()->route('cartItem.allItem')->with('success', 'ชำระเงินสำเร็จแล้ว');
    }
}
