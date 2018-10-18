<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use App\Models\Traits\TranslatableJson;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

/**
 * Class Role
 * @package App\Models
 */
class Role extends Model
{
    use HasTranslations;
    use TranslatableJson;
    use Cachable;

    /**
     * @var array
     */
    public $translatable = [
        'display_name',
        'description',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'order',
        'display_name',
        'description',
    ];

    /**
     * @var array
     */
    protected $with = [
        'permissions',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'can_edit',
        'can_delete'
    ];

    /**
     * Append the can_edit in this model.
     *
     * @return bool
     */
    public function getCanEditAttribute()
    {
        return true;
    }

    /**
     * Append the can_delete in this model.
     *
     * @return bool
     */
    public function getCanDeleteAttribute()
    {
        return Gate::check('delete roles');
    }

    /**
     * Get the permissions for the user role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Append the permissions of this model.
     *
     * @return mixed
     */
    public function getPermissionsAttribute()
    {
        return $this->permissions()->getResults()->pluck('name')->toArray();
    }

    /**
     * Set the display name to string.
     *
     * @return mixed|string
     */
    public function __toString()
    {
        return $this->display_name ?: $this->name;
    }
}
