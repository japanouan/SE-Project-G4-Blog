<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\Booking;
class BookingController extends Controller
{
    function checkTable(){
        if (Schema::hasTable((new Booking)->getTable())) {
            echo "Booking exists!";
        } 
        else{
            echo "Table does not exist!";
        }
      }
}
