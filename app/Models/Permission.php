<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use Cachable;

    protected $fillable = [
        'name',
    ];
}
