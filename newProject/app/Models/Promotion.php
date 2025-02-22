<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'Promotions';
    protected $primaryKey = 'promotion_id';
    protected $guarded = ['promotion_id'];
    protected $fillable = ['promotion_name', 'promotion_name', 'start_date', 'end_date', 'created_at', 'booking_id'];
}
