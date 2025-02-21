<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterStaffController extends Controller
{
    /**
     * แสดงฟอร์มการลงทะเบียนสำหรับ staff
     */
    public function showRegistrationForm()
    {
        return view('auth.register-staff');  // ฟอร์มลงทะเบียนสำหรับ staff
    }

    /**
     * ประมวลผลการลงทะเบียนสำหรับ staff
     */
    public function register(Request $request)
    {
        
        // ตรวจสอบข้อมูลที่กรอกมา
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'numeric'],
            'userType' => ['required', 'in:make-up artist,photographer,admin,shop owner'],  // ตรวจสอบประเภท staff
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // สร้างผู้ใช้ใหม่
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'userType' => $request->userType,  // กำหนดประเภทของ staff
            'password' => Hash::make($request->password),
            'status' => 'inactive'
        ]);

        // ล็อกอินผู้ใช้ใหม่
        Auth::login($user);

        // รีไดเร็กไปที่หน้า dashboard หรือหน้าที่ต้องการ
        return redirect()->route('dashboard');
    }
}
