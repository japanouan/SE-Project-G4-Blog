<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThaiOutfitCategory extends Model
{
    protected $table = 'ThaiOutfitCategories';
    protected $primaryKey = 'outfit_cate_id';
    protected $guarded = ['outfit_cate_id'];
    protected $fillable = ['created_at','category_id','outfit_id'];
    
    // Disable Laravel's automatic timestamp handling
    public $timestamps = false;
    // Relationship to ThaiOutfit
    public function outfit()
    {
        return $this->belongsTo(ThaiOutfit::class, 'outfit_id', 'outfit_id');
    }
    
    // Relationship to OutfitCategory
    public function category()
    {
        return $this->belongsTo(OutfitCategory::class, 'category_id', 'category_id');
    }
}
