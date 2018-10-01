<?php

namespace App\Http\Controllers\Backend;

use App\Models\Post;
use App\Repositories\Contracts\FormSubmissionRepository;
use App\Repositories\Contracts\PostRepository;
use App\Repositories\Contracts\UserRepository;

class DashboardController
{
    /**
     * @var PostsRepository
     */
    private $posts;

    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @var FormSubmissionRepository
     */
    private $formSubmissions;



    /**
     * DashboardController constructor.
     */
    public function __construct(PostRepository $posts, FormSubmissionRepository $formSubmissions, UserRepository $users)
    {
        $this->posts = $posts;
        $this->users = $users;
        $this->formSubmissions = $formSubmissions;
    }

    /**
     * Get the total draft posts.
     *
     * @return mixed
     */
    public function getDraftPostCounter()
    {
        return $this->posts->query()->whereStatus(Post::DRAFT)->count();
    }

    /**
     * Get the total pending posts.
     *
     * @return mixed
     */
    public function getPendingPostCounter()
    {
        return $this->posts->query()->whereStatus(Post::PENDING)->count();
    }

    /**
     * Get the total publish posts.
     *
     * @return mixed
     */
    public function getPublishedPostCounter()
    {
        return $this->posts->query()->whereStatus(Post::PUBLISHED)->count();
    }

    /**
     * Get the total active users.
     *
     * @return mixed
     */
    public function getActiveUserCounter()
    {
        return $this->users->query()->whereActive(true)->count();
    }

    /**
     * Get the total form submissions.
     *
     * @return mixed
     */
    public function getFormSubmissionCounter()
    {
        return $this->formSubmissions->query()->count();
    }
}
