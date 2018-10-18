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

/**
 * Class Post
 * @package App\Models
 */
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

    /**
     * @var string
     */
    public $sluggable = 'title';

    /**
     * @var array
     */
    public $editorFields = [
        'body',
    ];

    /**
     * @var string
     */
    public $editorCollectionName = 'editor images';

    /**
     * @var bool
     */
    public $asYouType = true;

    /**
     * @var array
     */
    public $translatable = [
        'title',
        'summary',
        'body',
        'slug',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'published_at',
        'unpublished_at',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'state',
        'status_label',
        'has_featured_image',
        'featured_image_path',
        'thumbnail_image_path',
        'can_edit',
        'can_delete',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status'             => 'integer',
        'pinned'             => 'boolean',
        'promoted'           => 'boolean',
        'has_featured_image' => 'boolean',
    ];

    /**
     * @var array
     */
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

    /**
     * @var array
     */
    protected $with = [
        'tags',
        'media',
        'owner',
        'meta',
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
        return Gate::check('delete', $this);
    }

    /**
     * Delete the meta data that associated in this model when this model is deleted.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleted(function (Post $post) {
            $post->meta->delete();
        });
    }

    /**
     * Define the states of this model.
     */
    const DRAFT = 0;
    const PENDING = 1;
    const PUBLISHED = 2;

    /**
     * Get the status of this model.
     *
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::DRAFT     => 'labels.backend.posts.statuses.draft',
            self::PENDING   => 'labels.backend.posts.statuses.pending',
            self::PUBLISHED => 'labels.backend.posts.statuses.published',
        ];
    }

    /**
     * Get the states of this model.
     *
     * @return array
     */
    public static function getStates()
    {
        return [
            self::DRAFT     => 'danger',
            self::PENDING   => 'warning',
            self::PUBLISHED => 'success',
        ];
    }

    /**
     * Append the status label of this model.
     *
     * @return mixed
     */
    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status];
    }

    /**
     * Append the state of this model.
     *
     * @return mixed
     */
    public function getStateAttribute()
    {
        return self::getStates()[$this->status];
    }

    /**
     * Append the published in this model.
     *
     * @return bool
     */
    public function getPublishedAttribute()
    {
        return self::PUBLISHED === $this->status;
    }

    /**
     * Append has_feature_image of this model.
     *
     * @return bool
     */
    public function getHasFeaturedImageAttribute()
    {
        return (bool) $this->getMedia('featured image')->first();
    }

    /**
     * Append the feature_image_path in this model.
     *
     * @return mixed|string
     */
    public function getFeaturedImagePathAttribute()
    {
        if ($media = $this->getMedia('featured image')->first()) {
            return str_replace(config('app.url'), '', $media->getUrl());
        }

        return '/images/placeholder.png';
    }

    /**
     * Append the thumbnail_image_path in this model.
     *
     * @return string
     */
    public function getThumbnailImagePathAttribute()
    {
        return image_template_url('small', $this->featured_image_path);
    }

    /**
     * Append the meta_title in this model.
     *
     * @return mixed
     */
    public function getMetaTitleAttribute()
    {
        return null !== $this->meta && !empty($this->meta->title) ? $this->meta->title : $this->title;
    }

    /**
     * Append the meta_description in this model.
     *
     * @return mixed
     */
    public function getMetaDescriptionAttribute()
    {
        return null !== $this->meta && !empty($this->meta->description) ? $this->meta->description : $this->summary;
    }

    /**
     * Owner of the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Set the published_at of this model.
     *
     * @param $value
     */
    public function setPublishedAtAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['published_at'] = Carbon::createFromFormat('Y-m-d H:i', $value);
        } else {
            $this->attributes['published_at'] = $value;
        }
    }

    /**
     * Set the unpublished_at of this model.
     *
     * @param $value
     */
    public function setUnpublishedAtAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['unpublished_at'] = Carbon::createFromFormat('Y-m-d H:i', $value);
        } else {
            $this->attributes['unpublished_at'] = $value;
        }
    }

    /**
     * Filter the published of this model.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query)
    {
        return $query->where('status', '=', self::PUBLISHED);
    }

    /**
     * Filter the owner of this model.
     *
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    public function scopeWithOwner(Builder $query, User $user)
    {
        return $query->where('user_id', '=', $user->id);
    }

    /**
     * Define the data that can be searchable.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id'      => $this->id,
            'title'   => $this->title,
            'summary' => $this->summary,
            'body'    => $this->body,
        ];
    }

    /**
     * Transform the attributes to array.
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = parent::toArray();

        TranslatableJson::getLocalizedTranslatableAttributes($this, $attributes);

        $attributes['body'] = Purify::clean($attributes['body']);
        $attributes['tags'] = $this->tags->pluck('name');

        return $attributes;
    }
}
