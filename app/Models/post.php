<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    protected $fillable = [
        'user_id',
        'content', 
        'image_url', 
        'like_count', 
        'dislike_count'
];

    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
