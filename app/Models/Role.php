<?php

namespace App\Models;

use App\Models\Traits\TranslatableJson;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Spatie\Translatable\HasTranslations;

class Role extends Model
{
    use HasTranslations;
    use TranslatableJson;
    use Cachable;

    public $translatable = [
        'display_name',
        'description',
    ];

    protected $fillable = [
        'name',
        'order',
        'display_name',
        'description',
    ];

    protected $with = [
        'permissions',
    ];

    protected $appends = [
        'can_edit',
        'can_delete'
    ];

    public function getCanEditAttribute()
    {
        return true;
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete roles');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function getPermissionsAttribute()
    {
        return $this->permissions()->getResults()->pluck('name')->toArray();
    }

    public function __toString()
    {
        return $this->display_name ?: $this->name;
    }
}
