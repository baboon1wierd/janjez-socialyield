<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $fillable = [
        'email', 'username', 'password', 'full_name', 'display_name',
        'avatar_url', 'bio', 'company', 'job_title', 'website',
        'phone', 'address', 'city', 'country', 'date_of_birth',
        'gender', 'is_active', 'is_verified', 'role',
        'do_agent_id', 'do_agent_token', 'do_agent_config',
        'referral_code', 'referred_by'
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret',
        'do_agent_token'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'is_suspended' => 'boolean',
        'permissions' => 'array',
        'do_agent_config' => 'array',
        'two_factor_backup_codes' => 'array',
        'date_of_birth' => 'date',
        'last_login' => 'datetime',
        'last_activity' => 'datetime',
        'account_locked_until' => 'datetime'
    ];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function socialPosts()
    {
        return $this->hasMany(SocialPost::class);
    }

    public function doAgent()
    {
        return $this->hasOne(DOAgent::class);
    }

    public function agentTasks()
    {
        return $this->hasMany(AgentTask::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function hasDigitalOceanAgent()
    {
        return !is_null($this->do_agent_id);
    }

    public function getAvatarUrlAttribute($value)
    {
        return $value ?: '/assets/default-avatar.png';
    }

    public function getFullNameAttribute($value)
    {
        return $value ?: $this->username;
    }
}
