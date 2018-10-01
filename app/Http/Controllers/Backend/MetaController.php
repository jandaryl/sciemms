<?php

namespace App\Http\Controllers\Backend;

use App\Models\Meta;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreMetaRequest;
use App\Http\Requests\UpdateMetaRequest;
use App\Repositories\Contracts\MetaRepository;

class MetaController extends BackendController
{
    /**
     * @var MetaRepository
     */
    protected $metas;

    /**
     * Create a new controller instance.
     *
     * @param MetaRepository $metas
     */
    public function __construct(MetaRepository $metas)
    {
        $this->metas = $metas;
    }

    /**
     * Search the data request.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function search(Request $request)
    {
        return $this->requestData($request);
    }

    /**
     * @param Meta $meta
     *
     * @return Meta
     */
    public function show(Meta $meta)
    {
        return $meta;
    }

    /**
     * Check the user permission then create a metas to the database.
     *
     * @param StoreMetaRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreMetaRequest $request)
    {
        $this->canCreateMetas();

        $this->metas->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.metas.created'));
    }

    /**
     * Check the user permission then update the data from database.
     *
     * @param Meta $meta
     * @param UpdateMetaRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Meta $meta, UpdateMetaRequest $request)
    {
        $this->canEditMetas();

        $this->metas->update($meta, $request->input());

        return $this->redirectResponse($request, __('alerts.backend.metas.updated'));
    }

    /**
     * Check the user permission then delete the metas.
     *
     * @param Meta $meta
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Meta $meta, Request $request)
    {
        $this->canDeleteMetas();

        $this->metas->destroy($meta);

        return $this->redirectResponse($request, __('alerts.backend.metas.deleted'));
    }


    /**
     * Check the user permission then perform the batch actions.
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
                return $this->deleteMetas($request, $ids);
                break;
        }

        return $this->redirectResponse($request, __('alerts.backend.actions.invalid'), 'error');
    }

    /**
     * Search the data.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function requestData(Request $request)
    {
        $query = $this->metas->query();

        $requestSearchQuery = new RequestSearchQuery($request, $query, [
            'title',
            'description',
        ]);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export([
                'route',
                'metable_type',
                'title',
                'description',
                'created_at',
                'updated_at',
            ],
                [
                    __('validation.attributes.route'),
                    __('validation.attributes.metable_type'),
                    __('validation.attributes.title'),
                    __('validation.attributes.description'),
                    __('labels.created_at'),
                    __('labels.updated_at'),
                ],
                'metas');
        }

        return $requestSearchQuery->result([
            'metas.id',
            'route',
            'metable_type',
            'metable_id',
            'title',
            'description',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * Check if the user can create metas.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canCreateMetas(): void
    {
        $this->authorize('create metas');
    }

    /**
     * Check if the user can edit metas.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canEditMetas(): void
    {
        $this->authorize('edit metas');
    }

    /**
     * Check if the user can delete metas.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canDeleteMetas(): void
    {
        $this->authorize('delete metas');
    }

    /**
     * Delete the metas.
     *
     * @param Request $request
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deleteMetas(Request $request, $ids)
    {
        $this->canDeleteMetas();

        $this->metas->batchDestroy($ids);

        return $this->redirectResponse($request, __('alerts.backend.metas.bulk_destroyed'));
    }
}
