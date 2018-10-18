<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Redirection
 * @package App\Models
 */
class Redirection extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'source',
        'active',
        'target',
        'type',
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
        return Gate::check('delete redirections');
    }

    /**
     * Filter the actives of this model.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActives(Builder $query)
    {
        return $query->where('active', '=', true);
    }
}
