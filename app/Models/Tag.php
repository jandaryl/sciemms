<?php

namespace App\Models;

use App\Models\Traits\TranslatableJson;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Tag extends \Spatie\Tags\Tag
{
    use TranslatableJson;
    use Cachable;
}
