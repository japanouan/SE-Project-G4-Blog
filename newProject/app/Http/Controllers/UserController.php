<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    // ฟังก์ชันแสดงรายการผู้ใช้
    public function index()
    {
        // ดึงข้อมูลผู้ใช้ทั้งหมด
        $users = User::all();

        // ส่งข้อมูลไปยัง view
        return view('admin.users.index', compact('users'));
    }

    // ฟังก์ชันแสดงฟอร์มแก้ไขผู้ใช้
    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);

        return view('admin.users.edit', compact('user'));
    }

    // ฟังก์ชันอัปเดตข้อมูลผู้ใช้
    public function update(Request $request, $user_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'username' => 'required|string|max:255|unique:Users,username,' . $user_id . ',user_id',
            'userType' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);



        // ค้นหาผู้ใช้และอัปเดตข้อมูล
        $user = User::find($user_id);
        $user->update($request->all());

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    // ฟังก์ชันเปลี่ยนสถานะผู้ใช้
    public function toggleStatus($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->status = ($user->status == 'active') ? 'inactive' : 'active';
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User status updated successfully!');
    }

    // accept form
    public function acceptance()
    {
        // ดึงข้อมูลผู้ใช้ทั้งหมด
        $users = User::where(function ($query) {
            $query->where('status', 'inactive')
                  ->where('is_newUser', true);
        })
        ->get();


        // ส่งข้อมูลไปยัง view
        return view('admin.users.acceptance', compact('users'));
    }

    // accept
    public function accept($user_id)
    {
        $user = User::where('user_id', $user_id)->firstOrFail();
        $user->status = 'active';
        $user->is_newUser = false;
        $user->save();

        return redirect()->route('admin.users.acceptance')->with('success', 'User accepted successfully.');
    }
    
    // accept
    public function decline($user_id)
    {
        $user = User::where('user_id', $user_id)->firstOrFail();
        $user->status = 'inactive';
        $user->is_newUser = false;
        $user->save();

        return redirect()->route('admin.users.acceptance')->with('success', 'User accepted successfully.');
    }
}
