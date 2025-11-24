<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id',
        'week_number',
        'hours_dsa',
        'hours_projects',
        'hours_career',
        'problems_solved',
        'commits',
        'outreach_count',
        'github_links',
        'other_links',
        'biggest_win',
        'biggest_excuse',
        'next_week_fix',
        'score',
        'verdict',
        'recommendations',
    ];

    protected $casts = [
        'recommendations' => 'array',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}
