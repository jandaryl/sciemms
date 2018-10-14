<?php

namespace App\Http\Requests;

use App\Repositories\Contracts\PostRepository;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * @var PostRepository
     */
    private $posts;

    /**
     * StorePostRequest constructor.
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
            'summary'        => 'required',
            'body'           => 'required',
            'featured_image' => 'nullable|mimes:jpeg,jpg,png,gif',
            'status'         => 'in:publish,draft',
            'published_at'   => 'nullable|date',
            'unpublished_at' => 'nullable|date',
            'pinned'         => 'boolean',
            'promoted'       => 'boolean',
        ];
    }

    public function persists()
    {
        $post = $this->posts->make(
            $this->only('title', 'summary', 'body', 'published_at', 'unpublished_at', 'pinned', 'promoted')
        );

        if ('publish' === $this->input('status')) {
            $this->posts->saveAndPublish($post, $this->input(), $this->file('featured_image'));
        } else {
            $this->posts->saveAsDraft($post, $this->input(), $this->file('featured_image'));
        }
    }
}
