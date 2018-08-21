<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use App\Models\Traits\TranslatableJson;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FormSetting extends Model
{
    use HasTranslations;
    use TranslatableJson;

    public $translatable = [
        'message',
    ];

    protected $fillable = [
        'name',
        'recipients',
        'message',
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
        return Gate::check('delete form_settings');
    }

    public function getArrayRecipientsAttribute()
    {
        return explode(',', $this->recipients);
    }

    public function getHtmlMessageAttribute()
    {
        $message = explode("\r\n", $this->message);

        return '<p>'.implode("</p>\r\n<p>", $message).'</p>'."\r\n";
    }
}
