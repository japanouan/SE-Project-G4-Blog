<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /*** Display the user's profile form.*/
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function show(Request $request): View
    {
        // ดึงข้อมูลของผู้ใช้ที่ล็อกอินอยู่
        $user = Auth::user();  // หรือใช้ User::find(Auth::id()) ถ้าต้องการดึงข้อมูลด้วย ID

        // ส่งข้อมูลผู้ใช้ไปยัง Blade view
        return view('profile.show', compact('user'));
    }


    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $user = Auth::user();

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->hasFile('profile_picture')) {
            // ลบรูปภาพเก่าหากมี
            if ($user->profile_picture_url) {
                Storage::delete($user->profile_picture_url);
            }
    
            // อัปโหลดรูปภาพใหม่และบันทึก path
            $path = $request->file('profile_picture')->store('profile_pictures', 'public'); // เก็บไฟล์ในโฟลเดอร์ public/profile_pictures
            $user->profile_picture_url = $path;
        }
        $request->user()->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }


    /**
     * Delete the user's account.
     */
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
