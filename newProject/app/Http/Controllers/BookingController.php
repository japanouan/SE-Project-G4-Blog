<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        // Get the current shop owner's active shop
        $shopId = \App\Models\Shop::where('shop_owner_id', Auth::id())
                     ->where('status', 'active')
                     ->first()->shop_id ?? null;
        
        if (!$shopId) {
            return redirect()->route('shopowner.shops.my-shop')
                ->with('error', 'คุณต้องมีร้านค้าที่ได้รับการอนุมัติก่อนใช้งานหน้านี้');
        }
        
        // Apply filters
        $status = $request->input('status');
        $dateRange = $request->input('date_range');
        $search = $request->input('search');
        
        $bookings = Booking::with(['user', 'orderDetails.cartItem.outfit'])
            ->where('shop_id', $shopId);
        
        // Apply status filter
        if ($status) {
            $bookings->where('status', $status);
        }
        
        // Apply date range filter
        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if (count($dates) == 2) {
                $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay();
                $bookings->whereBetween('purchase_date', [$startDate, $endDate]);
            }
        }
        
        // Apply search filter
        if ($search) {
            $bookings->where(function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('booking_id', 'like', "%{$search}%");
            });
        }
        
        // Order by newest first
        $bookings = $bookings->orderBy('purchase_date', 'desc')->paginate(10);
        
        return view('shopowner.bookings.index', compact('bookings', 'status'));
    }
    
    public function show($id)
    {
        $booking = Booking::with(['user', 'orderDetails.cartItem.outfit', 'shop', 'promotion'])
            ->findOrFail($id);
        
        // Check if this booking belongs to the current shop owner
        $shopId = \App\Models\Shop::where('shop_owner_id', Auth::id())
                     ->where('status', 'active')
                     ->first()->shop_id ?? null;
        
        if ($booking->shop_id != $shopId) {
            return redirect()->route('shopowner.bookings.index')
                ->with('error', 'คุณไม่มีสิทธิ์ดูการจองนี้');
        }
        
        return view('shopowner.bookings.show', compact('booking'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,partial paid,cancelled',
        ]);
        
        $booking = Booking::findOrFail($id);
        
        // Check if this booking belongs to the current shop owner
        $shopId = \App\Models\Shop::where('shop_owner_id', Auth::id())
                     ->where('status', 'active')
                     ->first()->shop_id ?? null;
        
        if ($booking->shop_id != $shopId) {
            return redirect()->route('shopowner.bookings.index')
                ->with('error', 'คุณไม่มีสิทธิ์แก้ไขการจองนี้');
        }
        
        $booking->status = $request->status;
        $booking->save();
        
        return redirect()->route('shopowner.bookings.show', $booking->booking_id)
            ->with('success', 'อัพเดทสถานะการจองเรียบร้อยแล้ว');
    }
    
    function checkTable(){
        // Check if the Booking table exists
        if (Schema::hasTable((new Booking)->getTable())) {
            echo "Booking table exists!<br>";

            // List of expected columns in the Booking table based on the image you provided
            $requiredColumns = [
                'booking_id', 'purchase_date', 'total_price', 'status', 
                'hasOverrented', 'created_at', 'shop_id', 'promotion_id'
            ];

            $missingColumns = [];

            // Check if each column exists
            foreach ($requiredColumns as $column) {
                if (!Schema::hasColumn((new Booking)->getTable(), $column)) {
                    $missingColumns[] = $column;
                }
            }

            if (empty($missingColumns)) {
                echo "All required columns are present!";
            } else {
                echo "Missing columns: " . implode(', ', $missingColumns);
            }
        } else {
            echo "Booking table does not exist!";
        }
    }


    // show booking for admin
    public function adminBooking(Request $request)
    {
        $query = Booking::with([
            'shop',
            'selectService',
            'orderDetails.cartItem.user'
        ]);
    
        if ($request->filled('search')) {
            $search = $request->search;
    
            $query->where(function ($q) use ($search) {
                // Search จาก booking fields
                $q->where('booking_id', 'like', "%{$search}%");
    
                // Search จากชื่อร้านค้า
                $q->orWhereHas('shop', function ($q2) use ($search) {
                    $q2->where('shop_name', 'like', "%{$search}%");
                });
    
                // Search จากชื่อผู้ใช้ (user → ผ่าน cartItem)
                $q->orWhereHas('orderDetails.cartItem.user', function ($q3) use ($search) {
                    $q3->where('name', 'like', "%{$search}%");
                });
            });
        }
    
        $bookings = $query->orderBy('booking_id', 'desc')->get();
    
        return view('admin.booking.booking', [
            'bookings' => $bookings,
        ]);
    }
    

    // admin show orderdetails
    public function adminOrderDetails($id){
        $booking = Booking::with([
            'shop',
            'promotion',
            'orderDetails.cartItem.user',  // preload แบบซ้อน
        ])->findOrFail($id);
        

        // dd($booking->shop,$booking->promotion);
        // dd($booking,$booking->orderDetails);

        return view('admin.booking.detail', [
            'booking' => $booking,
            'orderdetails' => $booking->orderDetails,
            'shop' => $booking->shop,
            'promotion' => $booking->promotion,
            'user' => $booking->orderDetails[0]->cartItem->user,
        ]);
    }
}
