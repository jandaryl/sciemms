<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class FormSubmission extends Model
{
    use Cachable;

    protected $appends = [
        'can_edit',
        'can_delete'
    ];

    public function getCanEditAttribute()
    {
        return false;
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete form_submissions');
    }

    public function getFormattedDataAttribute()
    {
        return json_decode($this->data);
    }
}
