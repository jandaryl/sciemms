<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialLogin extends Model
{
    use Cachable;
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
    ];
}
