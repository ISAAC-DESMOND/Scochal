<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class team_msgs extends Model
{
    /** @use HasFactory<\Database\Factories\TeamMsgsFactory> */
    use HasFactory;
    protected $fillable = [
        'team_id',
        'user_id',
        'message'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
