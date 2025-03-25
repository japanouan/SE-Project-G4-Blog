<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Booking;
use App\Models\SelectOutfitDetail;
use App\Models\ThaiOutfit;
use App\Models\CartItem;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Models\CustomerAddress;
use App\Models\Address;

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
        $customerId = auth()->id();
    
        $addresses = CustomerAddress::with('address')
            ->where('customer_id', $customerId)
            ->get();
    
        return view('profile.customer.address', compact('addresses'));
    }

    public function createAddress()
    {
        return view('profile.customer.createAddress');
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'AddressName' => 'required|string|max:255',
            'Province' => 'required|string',
            'District' => 'required|string',
            'Subdistrict' => 'required|string',
            'PostalCode' => 'required',
            'HouseNumber' => 'required|string',
            'Street' => 'nullable|string'
        ]);

        $address = Address::create($request->only(['Province', 'District', 'Subdistrict', 'PostalCode', 'HouseNumber', 'Street']));

        CustomerAddress::create([
            'customer_id' => Auth::id(),
            'AddressID' => $address->AddressID,
            'AddressName' => $request->AddressName
        ]);

        return redirect()->route('profile.customer.address.index')->with('success', 'เพิ่มที่อยู่เรียบร้อยแล้ว');
    }

    public function editAddress($cus_address_id)
    {
        $cusAddress = CustomerAddress::with('address')->findOrFail($cus_address_id);

        if ($cusAddress->customer_id != Auth::id()) {
            abort(403);
        }

        return view('profile.customer.editAddress', compact('cusAddress'));
    }

    public function updateAddress(Request $request, $cus_address_id)
    {
        $request->validate([
            'AddressName' => 'required|string|max:255',
            'Province' => 'required|string',
            'District' => 'required|string',
            'Subdistrict' => 'required|string',
            'PostalCode' => 'required',
            'HouseNumber' => 'required|string',
            'Street' => 'nullable|string'
        ]);

        $cusAddress = CustomerAddress::with('address')->findOrFail($cus_address_id);

        if ($cusAddress->customer_id != Auth::id()) {
            abort(403);
        }

        $cusAddress->update(['AddressName' => $request->AddressName]);
        $cusAddress->address->update($request->only(['Province', 'District', 'Subdistrict', 'PostalCode', 'HouseNumber', 'Street']));

        return redirect()->route('profile.customer.address.index')->with('success', 'แก้ไขที่อยู่เรียบร้อย');
    }

    public function deleteAddress($cus_address_id)
    {
        $cusAddress = CustomerAddress::findOrFail($cus_address_id);

        if ($cusAddress->customer_id != Auth::id()) {
            abort(403);
        }

        $cusAddress->address()->delete();
        $cusAddress->delete();

        return redirect()->route('profile.customer.address.index')->with('success', 'ลบที่อยู่เรียบร้อยแล้ว');
    }




    public function orderHistory()
    {
        $userId = Auth::user()->user_id;
        // ดึงข้อมูลการจอง (Booking) พร้อมกับข้อมูลที่เกี่ยวข้อง
        $bookings = Booking::with([
            'orderDetails.cartItem.thaioutfit_sizeandcolor.outfit.shop', 
            'selectService'
        ])
        ->where('user_id', $userId)
        ->get();
        

        // ส่งข้อมูลไปยังหน้า Blade
        return view('profile.customer.orderHistory', compact('bookings'));
    }

    public function orderDetail($bookingId)
    {
        // ดึงข้อมูล booking ตาม bookingId พร้อม relationship
        $booking = Booking::with([
            'orderDetails.cartItem.thaioutfit_sizeandcolor.outfit.shop',
            'selectService',
            'payments'
        ])->findOrFail($bookingId);

        // เพิ่มการตรวจสอบว่ามีชุดทดแทนหรือไม่
        $hasSuggestions = SelectOutfitDetail::where('booking_id', $bookingId)
            ->where('customer_id', Auth::id())
            ->exists();
    
        $hasPendingSuggestions = SelectOutfitDetail::where('booking_id', $bookingId)
            ->where('customer_id', Auth::id())
            ->where('status', 'Pending Selection')
            ->exists();

        // ตรวจสอบสถานะการชำระเงินสำหรับแต่ละ orderDetail
        foreach ($booking->orderDetails as $orderDetail) {
            $isPaid = false;
            // ตรวจสอบว่า booking_cycle ของ orderDetail ตรงกับ payment.booking_cycle หรือไม่
            if($booking->payments){
                foreach($booking->payments as $payment){
                    if($payment->booking_cycle == $orderDetail->booking_cycle){
                        $isPaid = true;
                    }
                }
            }
            // เพิ่ม property is_paid เพื่อใช้ใน Blade
            $orderDetail->is_paid = $isPaid;
        }
        
        // dd($booking);

        // ส่งข้อมูลไปยัง Blade พร้อมข้อมูลเพิ่มเติม
        return view('profile.customer.order-detail', compact(
            'booking', 
            'hasSuggestions',
            'hasPendingSuggestions'
        ));
    }
    /**
 * แสดงชุดทดแทนที่ได้รับการแนะนำ
 */
public function outfitSuggestions($bookingId)
{
    // ตรวจสอบว่าการจองเป็นของผู้ใช้ปัจจุบันหรือไม่
    $booking = Booking::where('booking_id', $bookingId)
        ->where('user_id', Auth::id())
        ->firstOrFail();
    
    // ดึงรายการสินค้าที่มีจำนวนไม่เพียงพอ (booking_cycle = 2)
    $unavailableItems = OrderDetail::with(['cartItem.outfit', 'cartItem.size', 'cartItem.color'])
        ->where('booking_id', $bookingId)
        ->where('booking_cycle', 2)
        ->get();
    
    // ดึงชุดทดแทนที่ได้รับการแนะนำจากร้านค้า
    $suggestions = SelectOutfitDetail::with(['outfit', 'size', 'color'])
        ->where('booking_id', $bookingId)
        ->where('customer_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get();
    
    return view('profile.customer.outfit-suggestions', compact('booking', 'unavailableItems', 'suggestions'));
}

    /*** ตอบรับหรือปฏิเสธชุดทดแทน*/
    public function confirmSelection(Request $request)
    {
        $request->validate([
            'selection_id' => 'required|exists:SelectOutfitsDetails,select_outfit_id',
            'action' => 'required|in:accept,reject'
        ]);
        
        $selectionId = $request->selection_id;
        $action = $request->action;
        
        // ดึงข้อมูลการเสนอชุดทดแทน
        $selection = SelectOutfitDetail::findOrFail($selectionId);
        
        // ตรวจสอบว่าเป็นของผู้ใช้ปัจจุบันหรือไม่
        if ($selection->customer_id != Auth::id()) {
            return back()->with('error', 'คุณไม่มีสิทธิ์ดำเนินการกับรายการนี้');
        }
        
        // ตรวจสอบว่าสถานะเป็น Pending Selection หรือไม่
        if ($selection->status != 'Pending Selection') {
            return back()->with('error', 'รายการนี้ได้รับการตอบรับหรือปฏิเสธไปแล้ว');
        }
        
        // อัพเดทสถานะตามการตัดสินใจของลูกค้า
        if ($action == 'accept') {
            $selection->status = 'Selected';
            $selection->save();
            
            // อาจจะอัพเดทสถานะการจองหรือรายการสั่งซื้อตามความเหมาะสม
            // เช่น หากทุกรายการได้รับการยืนยันแล้ว ให้เปลี่ยนสถานะการจองเป็น confirmed
            
            return redirect()->route('profile.customer.outfit-suggestions', ['bookingId' => $selection->booking_id])
                ->with('success', 'ยอมรับชุดทดแทนเรียบร้อยแล้ว ทางร้านค้าจะดำเนินการต่อไป');
        } else {
            $selection->status = 'Rejected';
            $selection->save();
            
            return redirect()->route('profile.customer.outfit-suggestions', ['bookingId' => $selection->booking_id])
                ->with('success', 'ปฏิเสธชุดทดแทนเรียบร้อยแล้ว ทางร้านค้าอาจเสนอชุดทดแทนอื่นเพิ่มเติม');
        }
    }
}
