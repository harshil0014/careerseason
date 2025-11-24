<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'track',
        'weeks',
        'current_week',
        'target_hours_per_week',
        'priority_tracks',
        'public_token',
    ];

    protected $casts = [
        'priority_tracks' => 'array',
    ];

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class);
    }
}
