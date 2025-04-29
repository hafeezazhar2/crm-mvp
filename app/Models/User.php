<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function createdLeads()
    {
        return $this->hasMany(Lead::class, 'created_by');
    }

    public function notes()
    {
        return $this->hasMany(LeadNote::class);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(UserRole::ADMIN->value);
    }

    public function isAdvisor(): bool
    {
        return $this->hasRole(UserRole::ADVISOR->value);
    }

    public function getIsAdminAttribute()
    {
        return $this->hasRole(UserRole::ADMIN->value);
    }
}
