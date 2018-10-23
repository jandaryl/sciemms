<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SocialLogin
 * @package App\Models
 */
class SocialLogin extends Model
{
    use Cachable;

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
    ];
}
