<?php

namespace App\Models;

use App\Notifications\ResetPassword as ResetPasswordNotification;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Gate;

class User extends Authenticatable
{
    use Notifiable;
    use Cachable;

    protected $with = [
        'roles',
    ];

    protected $dates = [
        'last_access_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'active',
        'locale',
        'timezone',
    ];

    protected $hidden = [
        'password',
        'confirmation_token',
        'remember_token',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $appends = [
        'avatar',
        'can_edit',
        'can_delete',
        'can_impersonate',
    ];

    public static function boot()
    {
        static::saving(function (User $model) {
            $model->slug = str_slug($model->name);
        });
    }

    public function getCanEditAttribute()
    {
        return ! $this->is_super_admin || 1 === auth()->id();
    }

    public function getCanDeleteAttribute()
    {
        return ! $this->is_super_admin && $this->id !== auth()->id() && (
            Gate::check('delete users')
        );
    }

    public function canAccessBackend()
    {
        return $this->id === auth()->id() && (
            Gate::check('access backend')
        );
    }

    public function isConfirmed()
    {
        return $this->confirmed === true;
    }

    public function isRemembered()
    {
        // Todo : Test the remember checking.
        return ! empty($this->remember_token);
    }

    public function getCanImpersonateAttribute()
    {
        if (Gate::check('impersonate users')) {
            return ! $this->is_super_admin
                && session()->get('admin_user_id') !== $this->id
                && $this->id !== auth()->id();
        }

        return false;
    }

    public function scopeActives(Builder $query)
    {
        return $query->where('active', '=', true);
    }

    public function getIsSuperAdminAttribute()
    {
        return 1 === $this->id;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getFormattedRolesAttribute()
    {
        return $this->is_super_admin
            ? __('labels.user.super_admin')
            : $this->roles->implode('display_name', ', ');
    }

    public function hasRole($name)
    {
        return $this->roles->contains('name', $name);
    }

    public function getPermissions()
    {
        $permissions = [];

        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permission) {
                if (! in_array($permission, $permissions, true)) {
                    $permissions[] = $permission;
                }
            }
        }

        foreach (config('permissions') as $name => $permission) {
            if (isset($permission['children']) && in_array($name, $permissions, true)) {
                $permissions = array_merge($permissions, $permission['children']);
            }
        }

        return collect($permissions);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function getProvider($provider)
    {
        return $this->providers->first(function (SocialLogin $item) use ($provider) {
            return $item->provider === $provider;
        });
    }

    public function providers()
    {
        return $this->hasMany(SocialLogin::class);
    }

    public function getAvatarAttribute()
    {
        $hash = md5($this->email);

        return "https://secure.gravatar.com/avatar/{$hash}?size=100&d=mm&r=g";
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function __toString()
    {
        return $this->name;
    }
}
