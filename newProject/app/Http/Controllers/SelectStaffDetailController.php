<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SelectService;
use App\Models\SelectStaffDetail;

class SelectStaffDetailController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->user_id; // ดึง userType ของผู้ใช้ที่ล็อกอิน

        $works = SelectStaffDetail::with('selectService.address') // ใช้ . ระหว่างชื่อความสัมพันธ์
        ->where('SelectStaffDetails.staff_id', $user_id)
        ->get();

            // ->toSql(); // แสดง query SQL

            // dd($works);  // ใช้ dd() เพื่อแสดงผล query ที่สร้างขึ้น
            $works = $works->sortBy('selectService.reservation_date');

        return view('makeup/schedule', compact('works'));
    }
}
