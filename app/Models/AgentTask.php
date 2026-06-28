<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentTask extends Model
{
    protected $fillable = [
        'do_agent_id', 'user_id', 'task_type', 'task_data',
        'priority', 'status', 'task_result', 'error_message',
        'attempts', 'assigned_at', 'started_at', 'completed_at'
    ];

    protected $casts = [
        'task_data' => 'array',
        'task_result' => 'array',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function doAgent(): BelongsTo
    {
        return $this->belongsTo(DOAgent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['queued', 'assigned']);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function retry(): bool
    {
        if ($this->attempts < 3) {
            $this->update([
                'status' => 'queued',
                'attempts' => $this->attempts + 1,
                'error_message' => null
            ]);
            return true;
        }
        return false;
    }

    public function markAsProcessing(): void
    {
        $this->update([
            'status' => 'processing',
            'started_at' => now()
        ]);
    }

    public function markAsCompleted($result = null): void
    {
        $this->update([
            'status' => 'completed',
            'task_result' => $result,
            'completed_at' => now()
        ]);
    }

    public function markAsFailed($error): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
            'completed_at' => now()
        ]);
    }
}
