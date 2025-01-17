<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sport_match extends Model
{
    /** @use HasFactory<\Database\Factories\SportMatchFactory> */
    use HasFactory;
    use HasFactory;

    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'match_date',
        'home_team_score',
        'away_team_score',
        'court_location',
        'status',
    ];

    protected $casts = [
        'match_date' => 'datetime',
    ];

    public function home()
    {
        return $this->belongsTo(team::class, 'home_team_id');
    }

    public function away()
    {
        return $this->belongsTo(team::class, 'away_team_id');
    }

}
