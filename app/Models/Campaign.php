<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'campaign_type',
        'budget', 'budget_spent', 'start_date', 'end_date',
        'platform_distribution', 'target_audience', 'status',
        'total_reach', 'total_engagement', 'total_conversions', 'roi'
    ];

    protected $casts = [
        'platform_distribution' => 'array',
        'target_audience' => 'array',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function socialPosts()
    {
        return $this->hasMany(SocialPost::class);
    }
}