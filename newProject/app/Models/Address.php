<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'Address';
    protected $primaryKey = 'AddressID';
    protected $guarded = ['AddressID'];
    protected $fillable = ['Province', 'District', 'Subdistrict', 'PostalCode', 'HouseNumber', 'Street', 'CreatedAt'];



    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id', 'user_id');
    }

}
