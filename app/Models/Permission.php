<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 * @package App\Models
 */
class Permission extends Model
{
    use Cachable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
