<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialPost extends Model
{
    protected $fillable = [
        'user_id', 'video_id', 'platform', 'platform_post_id',
        'post_type', 'content', 'media_urls', 'thumbnail_url',
        'caption', 'hashtags', 'mentions', 'location', 'tags',
        'is_ai_generated', 'ai_generation_data', 'ai_agent_id',
        'campaign_id', 'campaign_goal',
        'status', 'scheduled_for', 'published_at',
        'reach', 'impressions', 'likes', 'comments', 'shares',
        'saves', 'clicks', 'conversions', 'engagement_score', 'engagement_data',
        'do_agent_task_id', 'do_agent_response'
    ];

    protected $casts = [
        'media_urls' => 'array',
        'tags' => 'array',
        'mentions' => 'array',
        'ai_generation_data' => 'array',
        'engagement_data' => 'array',
        'do_agent_response' => 'array',
        'scheduled_for' => 'datetime',
        'published_at' => 'datetime',
        'engagement_score' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
