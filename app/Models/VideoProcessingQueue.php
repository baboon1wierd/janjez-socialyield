<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoProcessingQueue extends Model
{
    protected $table = 'video_processing_queue';

    protected $fillable = [
        'video_id', 'task_type', 'task_data',
        'status', 'attempts', 'do_agent_id', 'do_agent_result', 'processed_at'
    ];

    protected $casts = [
        'task_data' => 'array',
        'do_agent_result' => 'array',
        'processed_at' => 'datetime'
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }
}
