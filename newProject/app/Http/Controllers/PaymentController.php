<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

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

    public function index()
    {
        $bookings = Booking::with(['payments', 'orderDetails', 'selectService'])
            ->whereBelongsTo(auth()->user())
            ->where('status', 'confirmed') // ✅ เพิ่มเงื่อนไขตรงนี้
            ->orderBy('created_at', 'desc')
            ->get();

            foreach ($bookings as $booking) {
                // คำนวณยอดรวมจาก order details (ทั้งรอบ 1 และ 2)
                $total = $booking->orderDetails->sum('total');
            
                // รวมยอดจ่ายจริง เฉพาะที่ payment ถูกชำระแล้ว
                $paid = $booking->payments->where('status', 'paid')->sum('total');
            
                // คำนวณยอดค้าง
                $unpaid = max(0, $total - $paid); // กันลบแล้วติดลบ
            
                // สร้าง property ให้ใช้ใน Blade
                $booking->total_with_staff = $total; // รวมค่าบริการแล้วถ้ามี
                $booking->paid = $paid;
                $booking->unpaid = $unpaid;
            }
            

        return view('payment.index', compact('bookings'));
    }


    public function viewUpdate($booking_id, Request $request)
{
    $action = $request->query('action');

    $booking = Booking::with(['payments'])->where('booking_id', $booking_id)->firstOrFail();

    // ✅ ต้องใช้ ->filter() หรือ ->where() บน Collection
    if ($action === 'pay_remaining') {
        $payments = $booking->payments->where('status', 'paid')->where('booking_cycle', '2');
    }else{
        $payments = $booking->payments->where('status', 'unpaid');
    }
    

    return view('payment.viewUpdate', compact('payments', 'booking'));
}

public function updateMethod(Request $request, $id)
{
    $request->validate([
        'payment_method' => 'required|in:paypal,credit_card',
    ]);

    $payment = Payment::findOrFail($id);
    $booking = Booking::with(['orderDetails', 'payments'])->findOrFail($payment->booking_id);

    // คำนวณยอดรวมที่ต้องจ่ายทั้งหมด
    $totalRequired = $booking->orderDetails->sum('total');

    // คำนวณยอดที่จ่ายไปแล้ว (รวมรอบก่อนหน้า)
    $alreadyPaid = $booking->payments->where('status', 'paid')->sum('total');

    // คำนวณยอดค้างที่ต้องเพิ่ม
    $amountToAdd = max(0, $totalRequired - $alreadyPaid);

    // ✅ เพิ่มยอดค้างไปใน payment ปัจจุบัน
    $payment->payment_method = $request->payment_method;
    $payment->total += $amountToAdd; // 🔥 บวกยอดเพิ่มเข้าไป
    $payment->status = 'paid';
    $payment->save();

    // ✅ เปลี่ยน OrderDetail รอบ 2 → 1
    OrderDetail::where('booking_id', $booking->booking_id)
        ->where('booking_cycle', 2)
        ->update(['booking_cycle' => 1]);

    // ✅ ปิด hasOverrented
    $booking->hasOverrented = 0;
    $booking->save();

    return redirect()->route('payment.index')->with('success', 'อัปเดตการชำระเงินสำเร็จ');
}








}
