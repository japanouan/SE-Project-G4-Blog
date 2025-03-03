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
        // Get and sanitize sort parameters
        $orderBy = in_array($request->input('orderBy'), ['user_id', 'name', 'email', 'phone', 'username', 'userType', 'status']) 
            ? $request->input('orderBy') 
            : 'user_id';
            
        $direction = in_array(strtolower($request->input('direction')), ['asc', 'desc']) 
            ? strtolower($request->input('direction')) 
            : 'asc';
            
        $userTypes = $request->input('userType', []);
        
        // Start query
        $query = User::query();
        
        // Apply filters
        if (!empty($userTypes) && is_array($userTypes)) {
            $query->whereIn('userType', $userTypes);
        }
        
        // Apply sort
        $query->orderBy($orderBy, $direction);
        
        // Execute query
        $users = $query->get();
        
        // Pass all required variables to view for sorting and filtering to work
        return view('admin.users.index', compact('users', 'orderBy', 'direction', 'userTypes'));
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
    public function toggleStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $newStatus = $request->input('status');
            
            if (!in_array($newStatus, ['active', 'inactive'])) {
                throw new \Exception('Invalid status value');
            }
            
            $user->status = $newStatus;
            $user->save();
            
            // If it's an AJAX request, return JSON response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User status updated successfully',
                    'user' => [
                        'id' => $user->user_id,
                        'status' => $user->status
                    ]
                ]);
            }
            
            // For non-AJAX requests, redirect back with parameters
            return redirect()->route('admin.users.index', [
                'orderBy' => $request->input('orderBy'),
                'direction' => $request->input('direction'),
                'userType' => $request->input('userType')
            ])->with('success', 'User status updated successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update user status: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->withErrors(['error' => 'Failed to update user status: ' . $e->getMessage()]);
        }
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
