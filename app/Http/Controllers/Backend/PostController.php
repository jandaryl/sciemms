<?php

namespace App\Http\Controllers\Backend;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\PostRepository;

class PostController extends BackendController
{
    /**
     * @var PostRepository
     */
    protected $posts;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\Contracts\PostRepository $posts
     */
    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    /**
     * Fetch the latest Posts.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getLastestPosts()
    {
        $query = $this->postQuery();

        $this->filterOwnPosts($query);

        return $query->orderByDesc('created_at')->limit(10)->get();
    }

    /**
     * Search the data from user request.
     *
     * @param Request $request
     * @throws \Exception
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function search(Request $request)
    {
        return $this->requestData($request);
    }

    /**
     * Show the Post
     *
     * @param Post $post
     * @return Post
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Post $post)
    {
        $this->canViewPost($post);

        return $post;
    }

    /**
     * Save the Post to database.
     *
     * @param StorePostRequest $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StorePostRequest $request)
    {
        $this->canCreatePosts();

        $request->persists();

        return $this->redirectResponse($request, __('alerts.backend.posts.created'));
    }

    /**
     * Update and save the Post to database.
     *
     * @param Post $post
     * @param UpdatePostRequest $request
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Post $post, UpdatePostRequest $request)
    {
        $this->canUpdatePost($post);

        $request->persists($post);

        return $this->redirectResponse($request, __('alerts.backend.posts.updated'));
    }

    /**
     * Delete the Post
     * @param Post $post
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Post $post, Request $request)
    {
        $this->canDeletePost($post);

        $this->posts->destroy($post);

        return $this->redirectResponse($request, __('alerts.backend.posts.deleted'));
    }

    /**
     * Batch actions from the posts selected.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function batchAction(Request $request)
    {
        $action = $request->get('action');
        $ids = $request->get('ids');

        switch ($action) {
            case 'destroy':
                return $this->deletePosts($request, $ids);
                break;
            case 'publish':
                return $this->publishPosts($request, $ids);
                break;
            case 'pin':
                return $this->pinPosts($request, $ids);
                break;
            case 'promote':
                return $this->promotePosts($request, $ids);
                break;
        }

        return $this->redirectResponse($request, __('alerts.backend.actions.invalid'), 'error');
    }

    /**
     * Pin the Post
     *
     * @param Post $post
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function pinToggle(Post $post)
    {
        $this->canEditPosts();

        $post->update(['pinned' => !$post->pinned]);
    }

    /**
     * Promote the Post
     *
     * @param Post $post
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function promoteToggle(Post $post)
    {
        $this->canEditPosts();

        $post->update(['promoted' => !$post->promoted]);
    }

    /**
     * Check if user can't view the posts.
     *
     * @return bool
     */
    public function cannotViewPosts(): bool
    {
        return !Gate::check('view posts');
    }

    /**
     * Check if user can edit the posts.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canEditPosts(): void
    {
        $this->authorize('edit posts');
    }

    /**
     * Check if user can delete the posts.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canDeletePosts(): void
    {
        $this->authorize('delete posts');
    }

    /**
     * Check if user can view the post.
     *
     * @param Post $post
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canViewPost(Post $post): void
    {
        $this->authorize('view', $post);
    }

    /**
     * Check if user can create the posts.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canCreatePosts(): void
    {
        $this->authorize('create posts');
    }

    /**
     * Check if user can update the post.
     *
     * @param Post $post
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canUpdatePost(Post $post): void
    {
        $this->authorize('update', $post);
    }

    /**
     * Check if user can delete the post.
     *
     * @param Post $post
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canDeletePost(Post $post): void
    {
        $this->authorize('delete', $post);
    }

    /**
     * Check if user can delete the posts.
     *
     * @param Request $request
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deletePosts(Request $request, $ids)
    {
        $this->canDeletePosts();

        $this->posts->batchDestroy($ids);

        return $this->redirectResponse($request, __('alerts.backend.posts.bulk_destroyed'));
    }

    /**
     * Check if user can publish the posts.
     *
     * @return bool
     */
    public function canPublishPosts(): bool
    {
        return Gate::check('publish posts');
    }

    /**
     * Publish the posts.
     *
     * @param Request $request
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function publishPosts(Request $request, $ids)
    {
        $this->canEditPosts();

        $this->posts->batchPublish($ids);

        if ($this->canPublishPosts()) {
            return $this->redirectResponse($request, __('alerts.backend.posts.bulk_published'));
        }

        return $this->redirectResponse($request, __('alerts.backend.posts.bulk_pending', 'warning'));
    }

    /**
     * Pin the posts.
     *
     * @param Request $request
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function pinPosts(Request $request, $ids)
    {
        $this->canEditPosts();

        $this->posts->batchPin($ids);

        return $this->redirectResponse($request, __('alerts.backend.posts.bulk_pinned'));
    }

    /**
     * Promote the posts.
     *
     * @param Request $request
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function promotePosts(Request $request, $ids)
    {
        $this->canEditPosts();

        $this->posts->batchPromote($ids);

        return $this->redirectResponse($request, __('alerts.backend.posts.bulk_promoted'));
    }

    /**
     * Search the request data.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function requestData(Request $request)
    {
        $query = $this->postQuery();

        $query->join('users', 'users.id', '=', 'user_id');

        $query = $this->filterOwnPosts($query);

        $requestSearchQuery = new RequestSearchQuery($request, $query, [
            'title',
            'summary',
            'body',
        ]);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export(
                [
                    'title',
                    'status',
                    'pinned',
                    'promoted',
                    'posts.created_at',
                    'posts.updated_at',
                ],
                [
                    __('validation.attributes.title'),
                    __('validation.attributes.status'),
                    __('validation.attributes.pinned'),
                    __('validation.attributes.promoted'),
                    __('labels.created_at'),
                    __('labels.updated_at'),
                ],
                'posts'
            );
        }

        return $requestSearchQuery->result([
            'posts.id',
            'user_id',
            'users.name as owner',
            'title',
            'posts.slug',
            'status',
            'pinned',
            'promoted',
            'posts.created_at',
            'posts.updated_at',
        ]);
    }

    /**
     * Filter the own posts from query.
     *
     * @return PostRepository
     */
    public function filterOwnPosts($query)
    {
        if ($this->cannotViewPosts()) {
            // Filter to only current user's posts
            return $query->whereUserId(auth()->id());
        }

        return $query;
    }

    /**
     * Build the posts query.
     *
     * @return Builder
     */
    public function postQuery(): Builder
    {
        $query = $this->posts->query();

        return $query;
    }
}
