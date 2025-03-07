<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SelectService;
use App\Models\SelectStaffDetail;

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

    public function acceptJob(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'select_service_id' => 'required|exists:SelectServices,select_service_id',
    ]);

    // Get the service details
    $service = SelectService::findOrFail($request->input('select_service_id'));

    // Calculate the earning based on customer_count / required_staff * 2000
    $requiredStaff = ceil($service->customer_count / 3); // Assuming 3 customers per staff as per index method
    $earning = ($service->customer_count / $requiredStaff) * 2000;

    // Insert data into SelectStaffDetails table
    $staffDetail = new SelectStaffDetail();
    $staffDetail->select_staff_detail_id = null; // Auto-increment will handle this
    $staffDetail->earning = $earning;
    $staffDetail->created_at = now(); // Current timestamp
    $staffDetail->select_service_id = $request->input('select_service_id');
    $staffDetail->staff_id = Auth::user()->user_id;
    $staffDetail->save();

    // Prepare response data
    $response = [
        'success' => true,
        'message' => 'รับงานสำเร็จ!',
        'staff_count' => SelectStaffDetail::where('select_service_id', $service->select_service_id)->count(),
        'required_staff' => $requiredStaff,
    ];

    // Return JSON response
    return response()->json($response);
}
}
