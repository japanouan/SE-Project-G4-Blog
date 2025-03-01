<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThaiOutfit extends Model
{
    protected $table = 'ThaiOutfits';
    protected $primaryKey = 'outfit_id';
    protected $guarded = ['outfit_id'];
    protected $fillable = ['name', 'description', 'price', 'stock', 'image', 'status', 'created_at','shop_id'];

    // Disable Laravel's automatic timestamp handling
    public $timestamps = false;

    // Get shop that owns this outfit
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
    }
    
    // Get categories for this outfit
    public function categories()
    {
        return $this->belongsToMany(
            OutfitCategory::class, 
            'ThaiOutfitCategories', 
            'outfit_id', 
            'category_id'
        );
    }
}
