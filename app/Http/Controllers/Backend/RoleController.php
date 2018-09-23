d<?php

namespace App\Http\Controllers\Backend;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Repositories\Contracts\RoleRepository;

class RoleController extends BackendController
{
    /**
     * @var RoleRepository
     */
    protected $roles;


    /**
     * RoleController constructor.
     *
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roles)
    {
        $this->roles = $roles;
    }


    /**
     * Search the request data.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function search(Request $request)
    {
        return $this->requestData($request);
    }


    /**
     * Show the role.
     *
     * @param Role $role
     * @return Role
     */
    public function show(Role $role)
    {
        return $role;
    }

    /**
     * Get the user permissions.
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getPermissions()
    {
        return config('permissions');
    }


    /**
     * Store the role in the database.
     *
     * @param StoreRoleRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreRoleRequest $request)
    {
        $this->canCreateRoles();

        $this->roles->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.roles.created'));
    }


    /**
     * Update the role data from the database.
     *
     * @param Role $role
     * @param UpdateRoleRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Role $role, UpdateRoleRequest $request)
    {
        $this->canEditRoles();

        $this->roles->update($role, $request->input());

        return $this->redirectResponse($request, __('alerts.backend.roles.updated'));
    }


    /**
     * Delete the role data from the database.
     *
     * @param Role $role
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Role $role, Request $request)
    {
        $this->canDeleteRoles();

        $this->roles->destroy($role);

        return $this->redirectResponse($request, __('alerts.backend.roles.deleted'));
    }

    /**
     * Check if the user can create roles.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canCreateRoles(): void
    {
        $this->authorize('create roles');
    }

    /**
     * Check if the user can edit roles.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canEditRoles(): void
    {
        $this->authorize('edit roles');
    }

    /**
     * Check if the user can delete roles.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canDeleteRoles(): void
    {
        $this->authorize('delete roles');
    }

    /**
     * Search the data request.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function requestData(Request $request)
    {
        $query = $this->roles->query();

        $requestSearchQuery = new RequestSearchQuery($request, $query);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export([
                'name',
                'order',
                'display_name',
                'description',
                'created_at',
                'updated_at',
            ],
                [
                    __('validation.attributes.name'),
                    __('validation.attributes.order'),
                    __('validation.attributes.display_name'),
                    __('validation.attributes.description'),
                    __('labels.created_at'),
                    __('labels.updated_at'),
                ],
                'roles');
        }

        return $requestSearchQuery->result([
            'roles.id',
            'name',
            'order',
            'display_name',
            'description',
            'created_at',
            'updated_at',
        ]);
    }
}
