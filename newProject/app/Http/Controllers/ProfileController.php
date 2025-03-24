<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{

    public function show(Request $request): View
    {
        $user = Auth::user();  // หรือใช้ User::find(Auth::id()) ถ้าต้องการดึงข้อมูลด้วย ID

        return view('profile.show', compact('user'));
    }

    public function index()
    {

        $user = Auth::user();
        return view('profile.index', compact('user'));
    }


    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }
    public function editCus(Request $request): View
    {
        return view('profile.editCus', [
            'user' => $request->user(),
        ]);
    }



    public function update(Request $request)
    {
        // รับข้อมูลผู้ใช้ที่ล็อกอิน
        $user = $request->user();

        // การตรวจสอบข้อมูล
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', Rule::unique('Users', 'email')->ignore($user->user_id, 'user_id'),], // ใช้ user_id แทน id
            'phone' => ['required', 'numeric'],
            'gender' => ['nullable', 'in:male,female,others'],
            'profilePicture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);



        $user->fill($request->except(['password', 'profilePicture'])); // ยกเว้น profilePicture ออกจาก fill()

        // จัดการอัปโหลดรูปภาพแยกต่างหาก
        if ($request->hasFile('profilePicture')) {
            // ลบไฟล์เก่าถ้ามี
            if ($user->profilePicture && file_exists(public_path($user->profilePicture))) {
                unlink(public_path($user->profilePicture));
            }

            // กำหนดชื่อไฟล์ใหม่แบบสุ่ม
            $filename = Str::random(40) . '.' . $request->file('profilePicture')->getClientOriginalExtension();

            // ย้ายไฟล์ไปยังโฟลเดอร์ `public/images/profile-pic/`
            $request->file('profilePicture')->move(public_path('images/profile-pic'), $filename);

            // บันทึกพาธของรูปใหม่
            $user->profilePicture = 'images/profile-pic/' . $filename;
        }

        // อัปเดตรหัสผ่านหากมีการส่งมา
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // บันทึกการเปลี่ยนแปลง
        $user->save();

        if (Auth::user()->userType == 'customer') {
            return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
        }
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }


    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    public function customerAddress(Request $request)
    {
        //
    }



    public function orderHistory()
    {
        // ดึงข้อมูลการจอง (Booking) พร้อมกับข้อมูลที่เกี่ยวข้อง
        $bookings = Booking::with(['orderDetails.cartItem.thaioutfit_sizeandcolor.outfit.shop', 'selectService'])->get();

        // ส่งข้อมูลไปยังหน้า Blade
        return view('profile.customer.orderHistory', compact('bookings'));
    }

    public function orderDetail($bookingId)
    {
        // ดึงข้อมูล booking ตาม bookingId พร้อม relationship
        $booking = Booking::with([
            'orderDetails.cartItem.thaioutfit_sizeandcolor.outfit.shop',
            'selectService',
            'payment' // เพิ่ม relationship payment
        ])->findOrFail($bookingId);

        // ตรวจสอบสถานะการชำระเงินสำหรับแต่ละ orderDetail
        foreach ($booking->orderDetails as $orderDetail) {
            $isPaid = false;
            // ตรวจสอบว่า booking_cycle ของ orderDetail ตรงกับ payment.booking_cycle หรือไม่
            if($booking->payment){
                foreach($booking->payment as $payment){
                    if($payment->booking_cycle == $orderDetail->booking_cycle){
                        $isPaid = true;
                    }
                }
            }
            // เพิ่ม property is_paid เพื่อใช้ใน Blade
            $orderDetail->is_paid = $isPaid;
        }
        
        // dd($booking);

        // ส่งข้อมูลไปยัง Blade
        return view('profile.customer.order-detail', compact('booking'));
    }
}
