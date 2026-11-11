<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['id'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function customPermissions()
    {
        return $this->belongsToMany(Permission::class, 'admin_has_permissions');
    }

    /**
     * Check if user is Super Admin (unrestricted access)
     */
    public function isSuperAdmin(): bool
    {
        if (empty($this->role_id)) {
            return true;
        }
        return $this->role?->slug === 'super-admin';
    }

    /**
     * Check if staff has a specific permission
     */
    public function hasPermission(string $slug): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Direct staff permission override
        if ($this->customPermissions()->where('slug', $slug)->exists()) {
            return true;
        }

        // Role permission
        if ($this->role && $this->role->permissions()->where('slug', $slug)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Check if staff has any of the given permissions
     */
    public function hasAnyPermission(array $slugs): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ($slugs as $slug) {
            if ($this->hasPermission($slug)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if staff has a specific role
     */
    public function hasRole(string $roleSlug): bool
    {
        if ($this->isSuperAdmin() && $roleSlug === 'super-admin') {
            return true;
        }
        return $this->role?->slug === $roleSlug;
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('images/admins/' . $this->avatar);
        }
        return null;
    }
}
