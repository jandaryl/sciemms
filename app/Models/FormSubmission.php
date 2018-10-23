<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

/**
 * Class FormSubmission
 * @package App\Models
 */
class FormSubmission extends Model
{
    use Cachable;

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
        return false;
    }

    /**
     * Append the can_delete of this model.
     *
     * @return bool
     */
    public function getCanDeleteAttribute()
    {
        return Gate::check('delete form_submissions');
    }

    /**
     * Append the formatted_data of this model.
     *
     * @return mixed
     */
    public function getFormattedDataAttribute()
    {
        return json_decode($this->data);
    }
}
