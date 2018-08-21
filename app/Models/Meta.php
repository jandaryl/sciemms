<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use App\Models\Traits\TranslatableJson;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Meta extends Model
{
    use HasTranslations;
    use TranslatableJson;

    public $translatable = [
        'title',
        'description',
    ];

    protected $fillable = [
        'route',
        'title',
        'description',
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
        return Gate::check('delete metas');
    }

    public function metable()
    {
        return $this->morphTo();
    }
}
