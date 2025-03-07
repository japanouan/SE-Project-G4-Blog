<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;   
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SelectService;

class SelectServiceController extends Controller
{
    //

public function index()
{
    $userType = Auth::user()->userType; // ดึง userType ของผู้ใช้ที่ล็อกอิน

    $services = SelectService::with('address') // ดึงข้อมูลที่อยู่
        ->leftJoin('SelectStaffDetails as ss', 'SelectServices.select_service_id', '=', 'ss.select_service_id')
        ->select(
            'SelectServices.*',
            DB::raw('COUNT(ss.select_staff_detail_id) as staff_count'),
            DB::raw('CEIL(SelectServices.customer_count / 3) as required_staff') // จำนวนช่างที่ต้องการ
        )
        ->where('SelectServices.service_type', $userType) // กรองเฉพาะงานที่ตรงกับ userType
        ->groupBy('SelectServices.select_service_id', 'SelectServices.customer_count')
        ->havingRaw('staff_count < required_staff') // แสดงเฉพาะงานที่ยังขาดช่าง
        ->get();

    return view('makeup/work_list', compact('services'));
}

    

}
