<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SelectService;
use App\Models\SelectStaffDetail;

class AdminController extends Controller
{
    //
    public function showShopStatistics(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');

        // Shop: คำนวณยอดขายจาก Bookings และ OrderDetails (สำหรับ TOP 10)
        $ShopstatsTop10 = Booking::join('OrderDetails', 'Bookings.booking_id', '=', 'OrderDetails.booking_id')
            ->join('Shops', 'Bookings.shop_id', '=', 'Shops.shop_id')
            ->where('Bookings.purchase_date', 'LIKE', "%$month%")
            ->selectRaw('Shops.shop_id, Shops.shop_name, SUM(OrderDetails.total) as total_sales')
            ->groupBy('Shops.shop_id')
            ->orderByDesc('total_sales')
            ->take(10)
            ->get();

        // Shop: ข้อมูลทั้งหมดสำหรับรายงาน
        $ShopstatsAll = Booking::join('OrderDetails', 'Bookings.booking_id', '=', 'OrderDetails.booking_id')
            ->join('Shops', 'Bookings.shop_id', '=', 'Shops.shop_id')
            ->where('Bookings.purchase_date', 'LIKE', "%$month%")
            ->selectRaw('Shops.shop_id, Shops.shop_name, SUM(OrderDetails.total) as total_sales')
            ->groupBy('Shops.shop_id')
            ->get();

        return view('admin.statistics.shop', [
            'ShopstatsTop10' => $ShopstatsTop10,
            'ShopstatsAll' => $ShopstatsAll,
            'month' => $month,
        ]);
    }

    public function showPhotographerStatistics(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');

        // Photographer: ข้อมูลทั้งหมดสำหรับ TOP 10
        $photographerStatsTop10 = SelectService::where('service_type', 'photographer')
            ->where('reservation_date', 'LIKE', "%$month%")
            ->join('SelectStaffDetails', 'SelectServices.select_service_id', '=', 'SelectStaffDetails.select_service_id')
            ->where('SelectStaffDetails.finished_time', '<=', now())
            ->selectRaw('SelectStaffDetails.staff_id, SUM(SelectStaffDetails.earning) as total_payment')
            ->groupBy('SelectStaffDetails.staff_id')
            ->orderByDesc('total_payment')
            ->take(10)
            ->get();

        // Photographer: ข้อมูลทั้งหมด
        $photographerStatsAll = SelectService::where('service_type', 'photographer')
            ->where('reservation_date', 'LIKE', "%$month%")
            ->join('SelectStaffDetails', 'SelectServices.select_service_id', '=', 'SelectStaffDetails.select_service_id')
            ->where('SelectStaffDetails.finished_time', '<=', now())
            ->selectRaw('SelectStaffDetails.staff_id, SUM(SelectStaffDetails.earning) as total_payment')
            ->groupBy('SelectStaffDetails.staff_id')
            ->get();

        return view('admin.statistics.photographer', [
            'photographerStatsTop10' => $photographerStatsTop10,
            'photographerStatsAll' => $photographerStatsAll,
            'month' => $month,
        ]);
    }

    public function showMakeUpArtistStatistics(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');

        // Make-up Artist: ข้อมูลทั้งหมดสำหรับ TOP 10
        $makeUpArtistStatsTop10 = SelectService::where('service_type', 'make-up artist')
            ->where('reservation_date', 'LIKE', "%$month%")
            ->join('SelectStaffDetails', 'SelectServices.select_service_id', '=', 'SelectStaffDetails.select_service_id')
            ->where('SelectStaffDetails.finished_time', '<=', now())
            ->selectRaw('SelectStaffDetails.staff_id, SUM(SelectStaffDetails.earning) as total_payment')
            ->groupBy('SelectStaffDetails.staff_id')
            ->orderByDesc('total_payment')
            ->take(10)
            ->get();

        // Make-up Artist: ข้อมูลทั้งหมด
        $makeUpArtistStatsAll = SelectService::where('service_type', 'make-up artist')
            ->where('reservation_date', 'LIKE', "%$month%")
            ->join('SelectStaffDetails', 'SelectServices.select_service_id', '=', 'SelectStaffDetails.select_service_id')
            ->where('SelectStaffDetails.finished_time', '<=', now())
            ->selectRaw('SelectStaffDetails.staff_id, SUM(SelectStaffDetails.earning) as total_payment')
            ->groupBy('SelectStaffDetails.staff_id')
            ->get();

        return view('admin.statistics.makeup', [
            'makeUpArtistStatsTop10' => $makeUpArtistStatsTop10,
            'makeUpArtistStatsAll' => $makeUpArtistStatsAll,
            'month' => $month,
        ]);
    }
}
