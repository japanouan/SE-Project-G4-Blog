<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
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
        // dd($request->all());
    
        // การตรวจสอบข้อมูล
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email',Rule::unique('Users','email')->ignore($user->user_id,'user_id'),], // ใช้ user_id แทน id
            'phone' => ['required', 'numeric'],
            'gender' => ['nullable', 'in:male,female,others'],
            'profilePicture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);
        // dd($request);
        
        
    
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
}
