<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'video_url', 'thumbnail_url',
        'platform', 'platform_video_id', 'duration', 'resolution',
        'file_size', 'mime_type', 'tags', 'categories',
        'is_ai_generated', 'ai_model', 'ai_generation_data', 'ai_agent_id',
        'views', 'likes', 'comments', 'shares', 'engagement_data',
        'status', 'published_at', 'processed_at',
        'do_agent_task_id', 'do_agent_response', 'do_agent_processed_at'
    ];

    protected $casts = [
        'tags' => 'array',
        'categories' => 'array',
        'ai_generation_data' => 'array',
        'engagement_data' => 'array',
        'do_agent_response' => 'array',
        'published_at' => 'datetime',
        'processed_at' => 'datetime',
        'do_agent_processed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function socialPosts(): HasMany
    {
        return $this->hasMany(SocialPost::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeAIGenerated($query)
    {
        return $query->where('is_ai_generated', true);
    }
}
