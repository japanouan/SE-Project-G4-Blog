<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
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
}
