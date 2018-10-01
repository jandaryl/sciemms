<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;

class UserController extends BackendController
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * Create a new controller instance.
     *
     * @param UserRepository                             $users
     * @param \App\Repositories\Contracts\RoleRepository $roles
     */
    public function __construct(UserRepository $users, RoleRepository $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }


    /**
     * Search the data from the request.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function search(Request $request)
    {
        return $this->requestData($request);
    }


    /**
     * Show the user.
     *
     * @param User $user
     * @return User
     */
    public function show(User $user)
    {
        $this->cannotEditUser($user);

        return $user;
    }

    /**
     * Get all the roles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRoles()
    {
        return $this->roles->getAllowedRoles();
    }


    /**
     * Store the user request to database.
     *
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreUserRequest $request)
    {
        $this->canCreateUsers();

        $this->users->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.users.created'));
    }


    /**
     * Update the user data from database.
     *
     * @param User $user
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $this->canEditUsers();

        $this->users->update($user, $request->input());

        return $this->redirectResponse($request, __('alerts.backend.users.updated'));
    }


    /**
     * Delete the user from database.
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(User $user, Request $request)
    {
        $this->canDeleteUsers();

        $this->users->destroy($user);

        return $this->redirectResponse($request, __('alerts.backend.users.deleted'));
    }


    /**
     * Impersonate the users.
     *
     * @param User $user
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function impersonate(User $user)
    {
        $this->canImpersonateUsers();

        return $this->users->impersonate($user);
    }


    /**
     * Perform a batch action in users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function batchAction(Request $request)
    {
        $action = $request->get('action');
        $ids = $request->get('ids');

        switch ($action) {
            case 'destroy':
                return $this->deleteUsers($request, $ids);
                break;
            case 'enable':
                return $this->enableUsers($request, $ids);
                break;
            case 'disable':
                return $this->disableUsers($request, $ids);
                break;
        }

        return $this->redirectResponse($request, __('alerts.backend.actions.invalid'), 'error');
    }

    /**
     * Toggle the active user.
     *
     * @param User $user
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function activeToggle(User $user)
    {
        $this->canEditUsers();

        $user->update(['active' => ! $user->active]);
    }

    /**
     * Search the data.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function requestData(Request $request)
    {
        $requestSearchQuery = new RequestSearchQuery($request, $this->users->query(), [
            'name',
            'email',
        ]);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export([
                'name',
                'email',
                'active',
                'confirmed',
                'last_access_at',
                'created_at',
                'updated_at',
            ],
                [
                    __('validation.attributes.name'),
                    __('validation.attributes.email'),
                    __('validation.attributes.active'),
                    __('validation.attributes.confirmed'),
                    __('labels.last_access_at'),
                    __('labels.created_at'),
                    __('labels.updated_at'),
                ],
                'users');
        }

        return $requestSearchQuery->result([
            'id',
            'name',
            'email',
            'active',
            'confirmed',
            'last_access_at',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * Check the user if it cannot edit the user.
     *
     * @param User $user
     */
    public function cannotEditUser(User $user): void
    {
        if ( ! $user->can_edit) {
            // Only Super admin can access himself
            abort(403);
        }
    }

    /**
     * Check the user if it can create the users.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canCreateUsers(): void
    {
        $this->authorize('create users');
    }

    /**
     * Check the user if it can edit the users.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canEditUsers(): void
    {
        $this->authorize('edit users');
    }

    /**
     * Check the user if it can delete the users.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canDeleteUsers(): void
    {
        $this->authorize('delete users');
    }

    /**
     * Check the user if it can impersonate the users.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canImpersonateUsers(): void
    {
        $this->authorize('impersonate users');
    }


    /**
     * Check the user if it can  delete the users.
     *
     * @param Request $request
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deleteUsers(Request $request, $ids)
    {
        $this->canDeleteUsers();

        $this->users->batchDestroy($ids);

        return $this->redirectResponse($request, __('alerts.backend.users.bulk_destroyed'));
    }

    /**
     * Check the user if it can enable the users.
     *
     * @param Request $request
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function enableUsers(Request $request, $ids)
    {
        $this->canEditUsers();

        $this->users->batchEnable($ids);

        return $this->redirectResponse($request, __('alerts.backend.users.bulk_enabled'));
    }

    /**
     * Check the user if it can disable the users.
     *
     * @param Request $request
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function disableUsers(Request $request, $ids)
    {
        $this->canEditUsers();

        $this->users->batchDisable($ids);

        return $this->redirectResponse($request, __('alerts.backend.users.bulk_disabled'));
    }
}
