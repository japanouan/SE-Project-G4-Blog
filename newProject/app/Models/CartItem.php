<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'CartItems';
    protected $primaryKey = 'cart_item_id';
    protected $guarded = ['cart_item_id'];
    protected $fillable = ['quantity', 'created_at', 'purchased_at', 'outfit_id', 'userId', 'size_id', 'color_id'];

    public function outfit()
    {
        return $this->belongsTo(ThaiOutfit::class, 'outfit_id', 'outfit_id');
    }

    // ความสัมพันธ์กับ Size
    public function size()
    {
        return $this->belongsTo(ThaiOutfitSize::class, 'size_id', 'size_id');
    }

    // ความสัมพันธ์กับ Color
    public function color()
    {
        return $this->belongsTo(ThaiOutfitColor::class, 'color_id', 'color_id');
    }

    
}
