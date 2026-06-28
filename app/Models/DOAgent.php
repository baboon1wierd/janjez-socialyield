<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DOAgent extends Model
{
    protected $table = 'do_agents';

    protected $fillable = [
        'user_id', 'agent_name', 'agent_type', 'agent_id',
        'api_key', 'api_secret', 'agent_endpoint', 'agent_config',
        'status', 'is_verified', 'last_heartbeat', 'last_used_at',
        'capabilities', 'supported_tasks', 'performance_metrics',
        'max_concurrent_tasks', 'current_tasks', 'total_tasks_processed',
        'resource_usage'
    ];

    protected $casts = [
        'agent_config' => 'array',
        'capabilities' => 'array',
        'supported_tasks' => 'array',
        'performance_metrics' => 'array',
        'resource_usage' => 'array',
        'is_verified' => 'boolean',
        'last_heartbeat' => 'datetime',
        'last_used_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(AgentTask::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'active' && $this->current_tasks < $this->max_concurrent_tasks;
    }

    public function getCapacity(): int
    {
        return $this->max_concurrent_tasks - $this->current_tasks;
    }
}
