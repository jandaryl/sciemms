<?php

namespace App\Http\Requests;

use App\Models\Post;
use App\Repositories\Contracts\PostRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\Builder;

class UpdatePostRequest extends FormRequest
{
    /**
     * @var PostRepository
     */
    private $posts;

    /**
     * UpdatePostRequest constructor.
     */
    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    /**
     * Determine if the meta is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'          => 'required',
            'featured_image' => 'nullable|mimes:jpeg,jpg,png,gif',
            'status'         => 'in:publish,draft',
            'published_at'   => 'nullable|date',
            'unpublished_at' => 'nullable|date',
            'pinned'         => 'boolean',
            'promoted'       => 'boolean',
        ];
    }

    public function persists(Post $post)
    {
        $post->fill(
            $this->only('title', 'summary', 'body', 'published_at', 'unpublished_at', 'pinned', 'promoted')
        );

        if ($this->isPublish()) {
            $this->posts->saveAndPublish($post, $this->input(), $this->file('featured_image'));
        }

        if ($this->isDraft()) {
            $this->posts->saveAsDraft($post, $this->input(), $this->file('featured_image'));
        }
    }

    /**
     * @return bool
     */
    public function isPublish(): bool
    {
        return $this->input('status') === 'publish';
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->input('status') === 'draft';
    }
}
