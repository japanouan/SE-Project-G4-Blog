<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        
        // Apply date range filter - Fixed to work with date-only format
        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if (count($dates) == 2) {
                try {
                    // Create Carbon instances from the formatted dates
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
                    
                    // Apply the date range filter to purchase_date
                    $bookings->whereDate('purchase_date', '>=', $startDate->toDateString())
                             ->whereDate('purchase_date', '<=', $endDate->toDateString());
                } catch (\Exception $e) {
                    // Handle date parsing errors silently
                }
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
            'status' => 'required|in:pending,confirmed,partial paid,cancelled,completed',
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

    public function stats(Request $request)
    {
        // Get the current shop owner's active shop
        $shopId = \App\Models\Shop::where('shop_owner_id', Auth::id())
                        ->where('status', 'active')
                        ->first()->shop_id ?? null;
        
        if (!$shopId) {
            return redirect()->route('shopowner.shops.my-shop')
                ->with('error', 'คุณต้องมีร้านค้าที่ได้รับการอนุมัติก่อนใช้งานหน้านี้');
        }

        // Get period from request, default to 'daily'
        $period = $request->input('period', 'daily');
        
        // Get bookings for the shop with confirmed status
        $query = Booking::where('shop_id', $shopId)
                        ->whereIn('status', ['confirmed', 'partial paid'])
                        ->orderBy('purchase_date', 'desc');
        
        // Apply date filtering based on period
        $today = now();
        $startDate = null;
        $endDate = $today;
        
        switch ($period) {
            case 'daily':
                $startDate = $today->copy()->startOfDay();
                $labelFormat = 'H'; // Hour format
                $groupByFormat = 'H'; // Group by hour
                break;
            case 'weekly':
                $startDate = $today->copy()->startOfWeek();
                $labelFormat = 'D'; // Day name format (Mon, Tue, etc.)
                $groupByFormat = 'w'; // Group by day of week (0=Sunday, 6=Saturday)
                break;
            case 'monthly':
                $startDate = $today->copy()->startOfMonth();
                $labelFormat = 'd'; // Day of month format
                $groupByFormat = 'd'; // Group by day of month
                break;
            case 'yearly':
                $startDate = $today->copy()->startOfYear();
                $labelFormat = 'M'; // Month name format
                $groupByFormat = 'm'; // Group by month
                break;
        }
        
        // Filter bookings by date range
        $bookings = $query->whereBetween('purchase_date', [$startDate, $endDate])->get();
        
        // Calculate total earnings
        $totalEarnings = $bookings->sum('total_price');
        
        // Calculate total completed bookings
        $totalBookings = $bookings->count();
        
        // Prepare earnings data for chart
        $earningsData = [];
        
        if ($period == 'daily') {
            // Initialize hours with 0 earnings
            for ($i = 0; $i < 24; $i++) {
                $earningsData[sprintf("%02d", $i)] = 0;
            }
            
            // Group earnings by hour
            foreach ($bookings as $booking) {
                $hour = date('H', strtotime($booking->purchase_date));
                $earningsData[$hour] += $booking->total_price;
            }
        } elseif ($period == 'weekly') {
            // Initialize days with 0 earnings (1=Monday to 7=Sunday)
            for ($i = 1; $i <= 7; $i++) {
                $earningsData[$i] = 0;
            }
            
            // Group earnings by day of week
            foreach ($bookings as $booking) {
                $dayOfWeek = date('N', strtotime($booking->purchase_date)); // 1 (for Monday) through 7 (for Sunday)
                $earningsData[$dayOfWeek] += $booking->total_price;
            }
        } elseif ($period == 'monthly') {
            // Initialize days with 0 earnings
            $daysInMonth = $today->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $earningsData[sprintf("%02d", $i)] = 0;
            }
            
            // Group earnings by day of month
            foreach ($bookings as $booking) {
                $dayOfMonth = date('d', strtotime($booking->purchase_date));
                $earningsData[$dayOfMonth] += $booking->total_price;
            }
        } elseif ($period == 'yearly') {
            // Initialize months with 0 earnings
            for ($i = 1; $i <= 12; $i++) {
                $earningsData[sprintf("%02d", $i)] = 0;
            }
            
            // Group earnings by month
            foreach ($bookings as $booking) {
                $month = date('m', strtotime($booking->purchase_date));
                $earningsData[$month] += $booking->total_price;
            }
        }
        
        return view('shopowner.stats.income', compact('period', 'totalEarnings', 'totalBookings', 'earningsData'));
    }
}
