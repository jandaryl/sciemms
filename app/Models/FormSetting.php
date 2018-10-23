<?php

namespace App\Models;

use Illuminate\Support\Facades\Gate;
use App\Models\Traits\TranslatableJson;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Class FormSetting
 * @package App\Models
 */
class FormSetting extends Model
{
    use HasTranslations;
    use TranslatableJson;

    /**
     * @var array
     */
    public $translatable = [
        'message',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'recipients',
        'message',
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
        return Gate::check('delete form_settings');
    }

    /**
     * Append the array_recipient of this model.
     *
     * @return array
     */
    public function getArrayRecipientsAttribute()
    {
        return explode(',', $this->recipients);
    }

    /**
     * Append the html_message of this model.
     *
     * @return string
     */
    public function getHtmlMessageAttribute()
    {
        $message = explode("\r\n", $this->message);

        return '<p>'.implode("</p>\r\n<p>", $message).'</p>'."\r\n";
    }
}
