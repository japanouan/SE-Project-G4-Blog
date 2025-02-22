<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'Bookings';
    protected $primaryKey = 'booking_id';
    protected $guarded = ['booking_id'];
    protected $fillable = ['purchase_date', 'total_price', 'status', 'hasOverrented', 'created_at', 'shop_id', 'promotion_id'];
}
