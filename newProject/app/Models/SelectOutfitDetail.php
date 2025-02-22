<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectOutfitDetail extends Model
{
    protected $table = 'SelectOutfitsDetails';
    protected $primaryKey = 'select_outfit_id';
    protected $guarded = ['select_outfit_id'];
    protected $fillable = ['status', 'quantity', 'created_at', 'chooser_id','customer_id'];
}
