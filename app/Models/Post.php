<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\Tags\HasTags;
use Laravel\Scout\Searchable;
use App\Models\Traits\Metable;
use App\Models\Traits\HasEditor;
use Illuminate\Support\Facades\Gate;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\TranslatableJson;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\HasTranslatableSlug;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Post extends Model implements HasMedia
{
    use Searchable;
    use Metable;
    use HasTags;
    use HasTranslations;
    use HasTranslatableSlug;
    use HasMediaTrait;
    use HasEditor;
    use Cachable;

    public $sluggable = 'title';

    public $editorFields = [
        'body',
    ];

    public $editorCollectionName = 'editor images';

    public $asYouType = true;

    public $translatable = [
        'title',
        'summary',
        'body',
        'slug',
    ];

    protected $dates = [
        'published_at',
        'unpublished_at',
    ];

    protected $appends = [
        'state',
        'status_label',
        'has_featured_image',
        'featured_image_path',
        'thumbnail_image_path',
        'can_edit',
        'can_delete',
    ];

    protected $casts = [
        'status'             => 'integer',
        'pinned'             => 'boolean',
        'promoted'           => 'boolean',
        'has_featured_image' => 'boolean',
    ];

    protected $fillable = [
        'status',
        'title',
        'summary',
        'body',
        'slug',
        'published_at',
        'unpublished_at',
        'pinned',
        'promoted',
    ];

    protected $with = [
        'tags',
        'media',
        'owner',
        'meta',
    ];

    public function getCanEditAttribute()
    {
        return true;
    }

    public function getCanDeleteAttribute()
    {
        return Gate::check('delete', $this);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleted(function (Post $post) {
            $post->meta->delete();
        });
    }

    const DRAFT = 0;
    const PENDING = 1;
    const PUBLISHED = 2;

    public static function getStatuses()
    {
        return [
            self::DRAFT     => 'labels.backend.posts.statuses.draft',
            self::PENDING   => 'labels.backend.posts.statuses.pending',
            self::PUBLISHED => 'labels.backend.posts.statuses.published',
        ];
    }

    public static function getStates()
    {
        return [
            self::DRAFT     => 'danger',
            self::PENDING   => 'warning',
            self::PUBLISHED => 'success',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status];
    }

    public function getStateAttribute()
    {
        return self::getStates()[$this->status];
    }

    public function getPublishedAttribute()
    {
        return self::PUBLISHED === $this->status;
    }

    public function getHasFeaturedImageAttribute()
    {
        return (bool) $this->getMedia('featured image')->first();
    }

    public function getFeaturedImagePathAttribute()
    {
        if ($media = $this->getMedia('featured image')->first()) {
            return str_replace(config('app.url'), '', $media->getUrl());
        }

        return '/images/placeholder.png';
    }

    public function getThumbnailImagePathAttribute()
    {
        return image_template_url('small', $this->featured_image_path);
    }

    public function getMetaTitleAttribute()
    {
        return null !== $this->meta && !empty($this->meta->title) ? $this->meta->title : $this->title;
    }

    public function getMetaDescriptionAttribute()
    {
        return null !== $this->meta && !empty($this->meta->description) ? $this->meta->description : $this->summary;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setPublishedAtAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['published_at'] = Carbon::createFromFormat('Y-m-d H:i', $value);
        } else {
            $this->attributes['published_at'] = $value;
        }
    }

    public function setUnpublishedAtAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['unpublished_at'] = Carbon::createFromFormat('Y-m-d H:i', $value);
        } else {
            $this->attributes['unpublished_at'] = $value;
        }
    }

    public function scopePublished(Builder $query)
    {
        return $query->where('status', '=', self::PUBLISHED);
    }

    public function scopeWithOwner(Builder $query, User $user)
    {
        return $query->where('user_id', '=', $user->id);
    }

    public function toSearchableArray()
    {
        return [
            'id'      => $this->id,
            'title'   => $this->title,
            'summary' => $this->summary,
            'body'    => $this->body,
        ];
    }

    public function toArray()
    {
        $attributes = parent::toArray();

        TranslatableJson::getLocalizedTranslatableAttributes($this, $attributes);

        $attributes['body'] = Purify::clean($attributes['body']);
        $attributes['tags'] = $this->tags->pluck('name');

        return $attributes;
    }
}
