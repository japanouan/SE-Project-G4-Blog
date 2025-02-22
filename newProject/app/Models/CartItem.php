<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'CartItems';
    protected $primaryKey = 'cart_item_id';
    protected $guarded = ['cart_item_id'];
    protected $fillable = ['quantity', 'created_at', 'purchased_at', 'purchased_at'];
}
