<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Image extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'image_url', 'thumbnail_url',
        'platform', 'width', 'height', 'file_size', 'mime_type',
        'tags', 'categories', 'colors',
        'is_ai_generated', 'ai_model', 'ai_generation_data', 'ai_agent_id',
        'views', 'likes', 'comments', 'shares',
        'status', 'published_at'
    ];

    protected $casts = [
        'tags' => 'array',
        'categories' => 'array',
        'colors' => 'array',
        'ai_generation_data' => 'array',
        'published_at' => 'datetime'
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
}
