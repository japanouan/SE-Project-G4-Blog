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

class ProfileController extends Controller
{

    public function show(Request $request): View
    {
        $user = Auth::user();  // หรือใช้ User::find(Auth::id()) ถ้าต้องการดึงข้อมูลด้วย ID

        return view('profile.show', compact('user'));
    }

    

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }



    public function update(Request $request)
    {
        dd();
        // รับข้อมูลผู้ใช้ที่ล็อกอิน
        $user = $request->user();
    
        // ตรวจสอบว่าไฟล์ถูกอัพโหลดหรือไม่
        if ($request->hasFile('profile_picture')) {
            // สร้างชื่อไฟล์ที่ไม่ซ้ำ
            $filename = Str::random(40) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
    
            // เก็บไฟล์ในโฟลเดอร์ 'images/profile-pic' ใน disk 'public'
            $path = $request->file('profile_picture')->storeAs('images/profile-pic', $filename, 'public');
    
            // บันทึก path ของไฟล์ที่เก็บไว้ในฐานข้อมูล
            $user->profile_picture = $path;
            $user->save();
        }
    
        // อัพเดทข้อมูลอื่น ๆ
        $user->fill($request->validated());
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
