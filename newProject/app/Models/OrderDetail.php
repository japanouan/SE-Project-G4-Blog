<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'OrderDetails';
    protected $primaryKey = 'orderDetail_id';
    protected $guarded = ['orderDetail_id'];
    protected $fillable = ['quantity', 'total', 'booking_cycle', 'created_at', 'booking_id', 'cart_item_id'];
}
