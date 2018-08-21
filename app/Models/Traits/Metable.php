<?php

namespace App\Models\Traits;

use App\Models\Meta;

trait Metable
{
    public function meta()
    {
        return $this->morphOne(Meta::class, 'metable');
    }
}
