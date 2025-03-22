<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $table = 'issues';
    protected $fillable = [
        'user_id', 
        'title', 
        'description', 
        'reply', 
        'status', 
        'created_at', 
        'updated_at', 
    ];

    // กำหนดว่า `user_id` จะถูกเชื่อมโยงกับ `id` ในตาราง `users`
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ความสัมพันธ์หนึ่งกับหลายกับตาราง `notifications`
    public function notifications()
    {
        return $this->hasMany(Notifications::class);
    }
}
