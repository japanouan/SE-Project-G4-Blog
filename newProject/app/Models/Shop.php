<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'Shops';
    protected $primaryKey = 'shop_id';
    protected $guarded = ['shop_id'];
    protected $fillable = ['shop_name', 'shop_description', 'shop_location', 'rental_terms', 'depositfee', 'penaltyfee','status', 'created_at','shop_owner_id'];
}
