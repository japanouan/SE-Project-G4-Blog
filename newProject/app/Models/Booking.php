<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'Bookings';
    protected $primaryKey = 'booking_id';
    protected $guarded = ['booking_id'];

    public $timestamps = false;

    protected $fillable = [
        'purchase_date', 
        'total_price',
        'amount_staff', 
        'status', 
        'hasOverrented', 
        'created_at', 
        'shop_id', 
        'promotion_id',
        'user_id',
        'pickup_date'
    ];

    // ความสัมพันธ์กับร้านค้า
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
    }

    // ความสัมพันธ์กับโปรโมชั่น
    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id', 'promotion_id');
    }

    // ความสัมพันธ์กับผู้ใช้
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // ความสัมพันธ์กับรายละเอียดออเดอร์
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'booking_id', 'booking_id');
    }
}
