<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use App\Models\Traits\TranslatableJson;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Class Meta
 * @package App\Models
 */
class Meta extends Model
{
    use HasTranslations;
    use TranslatableJson;

    /**
     * @var array
     */
    public $translatable = [
        'title',
        'description',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'route',
        'title',
        'description',
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
        return Gate::check('delete metas');
    }

    /**
     * Get all of the owning metable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function metable()
    {
        return $this->morphTo();
    }
}
