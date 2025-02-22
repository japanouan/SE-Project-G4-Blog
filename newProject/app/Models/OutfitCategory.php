<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutfitCategory extends Model
{
    protected $table = 'OutfitCategories';
    protected $primaryKey = 'category_id';
    protected $guarded = ['category_id'];
    protected $fillable = ['category_name', 'created_at'];
}
