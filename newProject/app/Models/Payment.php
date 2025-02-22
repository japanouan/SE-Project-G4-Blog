<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'Payments';
    protected $primaryKey = 'payment_id';
    protected $guarded = ['payment_id'];
    protected $fillable = ['payment_method', 'total', 'status', 'booking_cycle', 'created_at', 'booking_id'];
}
