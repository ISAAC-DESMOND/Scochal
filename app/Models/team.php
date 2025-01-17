<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\member_joins;
use App\Models\team_msgs;
class team extends Model
{
    protected $fillable = [
      'name',
      'description',
      'user_id'
    ];

    public function members()
    {
        return $this->hasMany(member_joins::class);
    }

    public function texts()
    {
        return $this->hasMany(team_msgs::class);
    }

    public function Manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;
}
