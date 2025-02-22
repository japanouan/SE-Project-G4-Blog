<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThaiOutfit extends Model
{
    protected $table = 'ThaiOutfits';
    protected $primaryKey = 'outfit_id';
    protected $guarded = ['outfit_id'];
    protected $fillable = ['name', 'description', 'price', 'stock', 'image', 'status', 'created_at'];
}
