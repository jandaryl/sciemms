<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Redirection extends Model
{

    protected $fillable = [
        'source',
        'active',
        'target',
        'type',
    ];

    protected $casts = [
        'active' => 'boolean',
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
        return Gate::check('delete redirections');
    }

    public function scopeActives(Builder $query)
    {
        return $query->where('active', '=', true);
    }
}
