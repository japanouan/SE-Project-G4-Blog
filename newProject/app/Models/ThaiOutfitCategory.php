<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThaiOutfitCategory extends Model
{
    protected $table = 'ThaiOutfitCategories';
    protected $primaryKey = 'outfit_cate_id';
    protected $guarded = ['outfit_cate_id'];
    protected $fillable = ['created_at','category_id','outfit_id'];
}
