<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

/**
 * Class User
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable;
    use Cachable;

    /**
     * @var array
     */
    protected $with = [
        'roles',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'last_access_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'active',
        'locale',
        'timezone',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'confirmation_token',
        'remember_token',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'avatar',
        'can_edit',
        'can_delete',
        'can_impersonate',
    ];

    /**
     * Set the slug value in model name when saving.
     */
    public static function boot()
    {
        static::saving(function (User $model) {
            $model->slug = str_slug($model->name);
        });
    }

    /**
     * Append the can_edit in this model.
     *
     * @return bool
     */
    public function getCanEditAttribute()
    {
        return !$this->is_super_admin || 1 === auth()->id();
    }

    /**
     * Append the can_delete in this model.
     *
     * @return bool
     */
    public function getCanDeleteAttribute()
    {
        return !$this->is_super_admin && $this->id !== auth()->id() && (
            Gate::check('delete users')
        );
    }

    /**
     * Check if can access backend.
     *
     * @return bool
     */
    public function canAccessBackend()
    {
        return $this->id === auth()->id() && (
            Gate::check('access backend')
        );
    }

    /**
     * Check if the user is confirmed.
     *
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->confirmed === true;
    }

    /**
     * Check the user if it is remembered.
     *
     * @return bool
     */
    public function isRemembered()
    {
        // Todo : Test the remember checking.
        return !empty($this->remember_token);
    }

    /**
     * Append the can_impersonate in this model.
     *
     * @return bool
     */
    public function getCanImpersonateAttribute()
    {
        if (Gate::check('impersonate users')) {
            return !$this->is_super_admin
                && session()->get('admin_user_id') !== $this->id
                && $this->id !== auth()->id();
        }

        return false;
    }

    /**
     * Filter the actives in this model.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActives(Builder $query)
    {
        return $query->where('active', '=', true);
    }

    /**
     * Append the is_superAdmin in this model.
     *
     * @return bool
     */
    public function getIsSuperAdminAttribute()
    {
        return 1 === $this->id;
    }

    /**
     * Roles of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Append the formatted roles in this model.
     *
     * @return array|null|string
     */
    public function getFormattedRolesAttribute()
    {
        return $this->is_super_admin
            ? __('labels.user.super_admin')
            : $this->roles->implode('display_name', ', ');
    }

    /**
     * Get the role of the user.
     *
     * @param $name
     * @return mixed
     */
    public function hasRole($name)
    {
        return $this->roles->contains('name', $name);
    }

    /**
     * Get the permissions of the user.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissions()
    {
        $permissions = [];

        // Collect the role permissions then
        // store it in this variable "$permissions".
        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permission) {
                if (!in_array($permission, $permissions, true)) {
                    $permissions[] = $permission;
                }
            }
        }

        // Collect the system permissions then
        // merge it in this variable "$permissions".
        foreach (config('permissions') as $name => $permission) {
            if (isset($permission['children']) && in_array($name, $permissions, true)) {
                $permissions = array_merge($permissions, $permission['children']);
            }
        }

        return collect($permissions);
    }

    /**
     * Send password reset notification to the user.
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Get the social login provider of the user.
     *
     * @param $provider
     * @return mixed
     */
    public function getProvider($provider)
    {
        return $this->providers->first(function (SocialLogin $item) use ($provider) {
            return $item->provider === $provider;
        });
    }

    /**
     * Providers of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function providers()
    {
        return $this->hasMany(SocialLogin::class);
    }

    /**
     * Append the avatar in this model.
     *
     * @return string
     */
    public function getAvatarAttribute()
    {
        $hash = md5($this->email);

        return "https://secure.gravatar.com/avatar/{$hash}?size=100&d=mm&r=g";
    }

    /**
     * Posts of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Set the user name to string.
     *
     * @return mixed|string
     */
    public function __toString()
    {
        return $this->name;
    }
}
