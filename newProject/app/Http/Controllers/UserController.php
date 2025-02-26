<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{


    // ฟังก์ชันแสดงรายการผู้ใช้
    public function index(Request $request)
    {
        // รับค่าการจัดเรียง
        $orderBy = $request->input('orderBy') ?: 'user_id';
        $direction = $request->input('direction') ?: 'asc';


        // รับค่า userType filter เป็น array (ถ้าไม่มีจะได้ array ว่าง)
        if (!empty($request->input('userType'))) {
            // ถ้ามีค่า userType ใน request ให้ใช้ค่านั้น
            $userTypes = $request->input('userType');
        } else {
            // ถ้าไม่มีเลย ให้ใช้ array ว่าง
            $userTypes = [];
        }


        $query = User::query();
        // dd($orderBy);

        // กรองตาม userType ถ้ามีการเลือก
        if (!empty($userTypes) && is_array($userTypes)) {
            $query->whereIn('userType', $userTypes);
        }

        // จัดเรียงตาม orderBy และ direction
        $query->orderBy($orderBy, $direction);
        // dd($query);

        $users = $query->get();
        // dd($users);

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
    public function toggleStatus(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        $user->status = $request->status; // รับค่าจากฟอร์ม
        $user->save();

        $orderBy = $request->input('orderBy', 'user_id');
        $direction = $request->input('direction', 'asc');
        $userTypes = $request->input('userType', []);

        return redirect()->route('admin.users.index', [
            'orderBy'  => $orderBy,
            'direction'=> $direction,
            'userType' => $userTypes, // Laravel จะทำการแปลง array เป็น query string ให้เอง
       ])->with('success', 'User status updated successfully!');
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

    // updateStatus
    public function updateStatus(Request $request, $user_id, $status)
    {
        $user = User::where('user_id', $user_id)->firstOrFail();
        $user->status = $status;
        $user->is_newUser = false;
        $user->save();

        return response()->json(['success' => true]);
    }
}
