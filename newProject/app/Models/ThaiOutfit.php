<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\ThaiOutfitSize;

class ThaiOutfit extends Model
{
    protected $table = 'ThaiOutfits';
    protected $primaryKey = 'outfit_id';
    protected $guarded = ['outfit_id'];
    protected $fillable = ['name', 'description', 'price', 'image', 'status', 'created_at', 'shop_id', 'depositfee', 'penaltyfee'];

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
    
    // Get all size and color combinations for this outfit
    public function sizeAndColors()
    {
        return $this->hasMany(ThaiOutfitSizeAndColor::class, 'outfit_id', 'outfit_id');
    }
    
    // Get total stock (sum of all size/color combinations)
    public function getTotalStockAttribute()
    {
        return $this->sizeAndColors()->sum('amount') ?? 0;
    }
    protected static function boot()
{
    parent::boot();
    
    // When deleting an outfit, also delete related size and color combinations
    static::deleting(function($outfit) {
        $outfit->sizeAndColors()->delete();
    });
}
}
