<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectStaffDetail extends Model
{
    protected $table = 'SelectStaffDetails';
    protected $primaryKey = 'select_staff_detail_id';
    protected $guarded = ['select_staff_detail_id'];
    protected $fillable = ['earning', 'created_at', 'select_service_id', 'staff_id'];
}
