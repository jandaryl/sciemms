<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\Models\Media;
use App\Repositories\Contracts\PostRepository;

/**
 * Class EloquentPostRepository.
 */
class EloquentPostRepository extends EloquentBaseRepository implements PostRepository
{
    /**
     * Construct the Post instance.
     *
     * @param Post $post
     */
    public function __construct(
        Post $post
    ) {
        parent::__construct($post);
    }

    /**
     * Publish the posts.
     *
     * @return mixed
     */
    public function published()
    {
        return $this->model
            ->published()
            ->with('owner')
            ->orderByDesc('pinned')
            ->orderByDesc('updated_at');
    }

    /**
     * Publish the posts by tag.
     *
     * @param Tag $tag
     *
     * @return mixed
     */
    public function publishedByTag(Tag $tag)
    {
        return $this->published()->withAnyTags($tag->name);
    }

    /**
     * Publish the posts by its owner.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function publishedByOwner(User $user)
    {
        return $this->published()->withOwner($user);
    }

    /**
     * Find the post data by slug.
     *
     * @param string $slug
     *
     * @return mixed
     */
    public function findBySlug($slug)
    {
        return $this->query()->whereSlug($slug)->first();
    }

    /**
     * Save and publish the Post.
     *
     * @param Post                               $post
     * @param array                              $input
     * @param \Illuminate\Http\UploadedFile|null $image
     *
     * @throws \App\Exceptions\GeneralException|\Exception|\Throwable
     *
     * @return mixed
     */
    public function saveAndPublish(Post $post, array $input, UploadedFile $image = null)
    {
        $post->status = Post::PUBLISHED;

        return $this->save($post, $input, $image);
    }

    /**
     * Save the Post as Draft.
     *
     * @param Post                               $post
     * @param array                              $input
     * @param \Illuminate\Http\UploadedFile|null $image
     *
     * @throws \App\Exceptions\GeneralException|\Exception|\Throwable
     *
     * @return mixed
     */
    public function saveAsDraft(Post $post, array $input, UploadedFile $image = null)
    {
        $post->status = Post::DRAFT;

        return $this->save($post, $input, $image);
    }

    /**
     * Save the Post in the database.
     *
     * @param Post                               $post
     * @param array                              $input
     * @param \Illuminate\Http\UploadedFile|null $image
     *
     * @throws \App\Exceptions\GeneralException|\Exception|\Throwable
     *
     * @return mixed
     */
    private function save(Post $post, array $input, UploadedFile $image = null)
    {
        if ($post->exists) {
            if (!Gate::check('update', $post)) {
                throw new GeneralException(__('exceptions.backend.posts.save'));
            }
        } else {
            $post->user_id = auth()->id();
        }

        if (Post::PUBLISHED === $post->status && !Gate::check('publish posts')) {
            // User with no publish permissions must go to moderation awaiting
            $post->status = Post::PENDING;
        }

        DB::transaction(function () use ($post, $input, $image) {
            if (!$post->save()) {
                throw new GeneralException(__('exceptions.backend.posts.save'));
            }

            if (isset($input['meta'])) {
                if (!$post->meta) {
                    $post->meta()->create($input['meta']);
                } else {
                    $post->meta->update($input['meta']);
                }
            } else {
                $post->meta()->create();
            }

            // Tags
            if (isset($input['tags'])) {
                $post->syncTags($input['tags']);
            }

            // Featured image
            /** @var Media $currentFeaturedImage */
            $currentFeaturedImage = $post->getMedia('featured image')->first();

            // Delete current image if replaced or delete asking
            if ($currentFeaturedImage && ($image || !$input['has_featured_image'])) {
                $currentFeaturedImage->delete();
            }

            if ($image) {
                $post->addMedia($image)
                    ->toMediaCollection('featured image');
            }

            return true;
        });

        return true;
    }

    /**
     * Delete the Post.
     *
     * @param Post $post
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function destroy(Post $post)
    {
        if (!$post->delete()) {
            throw new GeneralException(__('exceptions.backend.posts.delete'));
        }

        return true;
    }

    /**
     * Make a batch query.
     *
     * @param array $ids
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function batchQuery(array $ids)
    {
        $query = $this->query()->whereIn('id', $ids);

        return $query;
    }

    /**
     * Delete a batch of data.
     *
     * @param array $ids
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed
     */
    public function batchDestroy(array $ids)
    {
        DB::transaction(function () use ($ids) {
            $query = $this->batchQuery($ids);

            if (!Gate::check('delete posts')) {
                // Filter to only current user's posts
                $query->whereUserId(auth()->id());
            }

            /** @var Post[] $posts */
            $posts = $query->get();

            foreach ($posts as $post) {
                $this->destroy($post);
            }

            return true;
        });

        return true;
    }

    /**
     * Publish a batch of data.
     *
     * @param array $ids
     *
     * @throws \Throwable
     * @throws \Exception
     *
     * @return mixed
     */
    public function batchPublish(array $ids)
    {
        DB::transaction(function () use ($ids) {
            $query = $this->batchQuery($ids);

            if (!Gate::check('edit posts')) {
                // Filter to only current user's posts
                $query->whereUserId(auth()->id());
            }

            if (Gate::check('publish posts')) {
                if ($query->update(['status' => Post::PUBLISHED])) {
                    return true;
                }
            } else {
                // Set to moderation pending if no right to publish
                if ($query->update(['status' => Post::PENDING])) {
                    return true;
                }
            }

            throw new GeneralException(__('exceptions.backend.posts.update'));
        });
    }

    /**
     * Pin a batch of data.
     *
     * @param array $ids
     *
     * @throws \Throwable
     * @throws \Exception
     *
     * @return mixed
     */
    public function batchPin(array $ids)
    {
        DB::transaction(function () use ($ids) {
            $query = $this->batchQuery($ids);

            if (!Gate::check('edit posts')) {
                // Filter to only current user's posts
                $query->whereUserId(auth()->id());
            }

            if ($query->update(['pinned' => true])) {
                return true;
            }

            throw new GeneralException(__('exceptions.backend.posts.update'));
        });

        return true;
    }

    /**
     * Promote a batch of data.
     *
     * @param array $ids
     *
     * @throws \Throwable
     * @throws \Exception
     *
     * @return mixed
     */
    public function batchPromote(array $ids)
    {
        DB::transaction(function () use ($ids) {
            $query = $this->batchQuery($ids);

            if (!Gate::check('edit posts')) {
                // Filter to only current user's posts
                $query->whereUserId(auth()->id());
            }

            if ($query->update(['promoted' => true])) {
                return true;
            }

            throw new GeneralException(__('exceptions.backend.posts.update'));
        });

        return true;
    }
}
