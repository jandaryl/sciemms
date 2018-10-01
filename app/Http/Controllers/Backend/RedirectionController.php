<?php

namespace App\Http\Controllers\Backend;

use League\Csv\Reader;
use App\Models\Redirection;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreRedirectionRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateRedirectionRequest;
use App\Repositories\Contracts\RedirectionRepository;

class RedirectionController extends BackendController
{
    /**
     * @var RedirectionRepository
     */
    protected $redirections;

    /**
     * Create a new controller instance.
     *
     * @param RedirectionRepository $redirections
     */
    public function __construct(RedirectionRepository $redirections)
    {
        $this->redirections = $redirections;
    }


    /**
     * Search the data request from user.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function search(Request $request)
    {
        return $this->requestData($request);
    }


    /**
     * Show the redirection.
     *
     * @param Redirection $redirection
     * @return Redirection
     */
    public function show(Redirection $redirection)
    {
        return $redirection;
    }

    /**
     * Get the type of redirection.
     *
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getRedirectionTypes()
    {
        $redirections = config('redirections');

        array_walk($redirections, function (&$item) {
            $item = __($item);
        });

        return $redirections;
    }


    /**
     * Check the user permission then store the redirection to the database.
     *
     * @param StoreRedirectionRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreRedirectionRequest $request)
    {
        $this->canCreateRedirections();

        $this->redirections->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.redirections.created'));
    }


    /**
     * Check the user permission then update the redirection from the database.
     *
     * @param Redirection $redirection
     * @param UpdateRedirectionRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Redirection $redirection, UpdateRedirectionRequest $request)
    {
        $this->canEditRedirections();

        $this->redirections->update($redirection, $request->input());

        return $this->redirectResponse($request, __('alerts.backend.redirections.updated'));
    }


    /**
     * Check the user permission then delete the redirections.
     *
     * @param Redirection $redirection
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Redirection $redirection, Request $request)
    {
        $this->canDeleteRedirections();

        $this->redirections->destroy($redirection);

        return $this->redirectResponse($request, __('alerts.backend.redirections.deleted'));
    }


    /**
     * Check the user permission then perform the batch actions to redirections data.
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
                return $this->deleteRedirections($request, $ids);
                break;
            case 'enable':
                return $this->enableRedirections($request, $ids);
                break;
            case 'disable':
                return $this->disableRedirections($request, $ids);
                break;
        }

        return $this->redirectResponse($request, __('alerts.backend.actions.invalid'), 'error');
    }

    /**
     * Check the user permission then toggle the active redirections.
     *
     * @param Redirection $redirection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function activeToggle(Redirection $redirection)
    {
        $this->canEditRedirections();

        $redirection->update(['active' => ! $redirection->active]);
    }


    /**
     * Import the file object then convert it into file.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \League\Csv\Exception
     */
    public function import(Request $request)
    {
        $this->canCreateRedirections();

        $this->validate($request, [
            'import' => 'required',
        ]);

        $csv = $this->createFromFileObject($request);

        $this->persists($csv);

        return $this->redirectResponse($request, __('alerts.backend.redirections.file_imported'));
    }


    /**
     * Search the data.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function requestData(Request $request)
    {
        $requestSearchQuery = new RequestSearchQuery($request, $this->redirections->query(), [
            'source',
            'target',
        ]);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export([
                'source',
                'active',
                'target',
                'type',
                'created_at',
                'updated_at',
            ],
                [
                    __('validation.attributes.source_path'),
                    __('validation.attributes.active'),
                    __('validation.attributes.target_path'),
                    __('validation.attributes.redirect_type'),
                    __('labels.created_at'),
                    __('labels.updated_at'),
                ],
                'redirections');
        }

        return $requestSearchQuery->result([
            'id',
            'source',
            'active',
            'target',
            'type',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * Check if the user can create redirections.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canCreateRedirections(): void
    {
        $this->authorize('create redirections');
    }

    /**
     * Check if the user can edit redirections.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canEditRedirections(): void
    {
        $this->authorize('edit redirections');
    }

    /**
     * Check if the user can delete redirections.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canDeleteRedirections(): void
    {
        $this->authorize('delete redirections');
    }


    /**
     * Delete the redirection from database.
     *
     * @param Request $request
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deleteRedirections(Request $request, $ids)
    {
        $this->canDeleteRedirections();

        $this->redirections->batchDestroy($ids);

        return $this->redirectResponse($request, __('alerts.backend.redirections.bulk_destroyed'));
    }


    /**
     * Enable the redirections.
     *
     * @param Request $request
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function enableRedirections(Request $request, $ids)
    {
        $this->canEditRedirections();

        $this->redirections->batchEnable($ids);

        return $this->redirectResponse($request, __('alerts.backend.redirections.bulk_enabled'));
    }

    /**
     * Disable the redirections.
     *
     * @param Request $request
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function disableRedirections(Request $request, $ids)
    {
        $this->canEditRedirections();

        $this->redirections->batchDisable($ids);

        return $this->redirectResponse($request, __('alerts.backend.redirections.bulk_disabled'));
    }

    /**
     * Convert the data file object.
     *
     * @param Request $request
     * @return \League\Csv\AbstractCsv|Reader
     * @throws \League\Csv\Exception
     */
    public function createFromFileObject(Request $request)
    {
        $csv = Reader::createFromFileObject($request->file('import')->openFile())
            ->setHeaderOffset(0)
            ->setDelimiter(';');
        return $csv;
    }

    /**
     * Persists the redirection data to database.
     *
     * @param $row
     */
    public function saveRedirections($row): void
    {
        $this->redirections->store([
            'source' => $row['source'],
            'target' => $row['target'],
            'type' => Response::HTTP_MOVED_PERMANENTLY,
        ]);
    }


    /**
     * Save the redirection data to the database.
     *
     * @param $csv
     */
    public function persists($csv): void
    {
        foreach ($csv as $row) {
            if (isset($row['source'], $row['target'])) {
                $this->saveRedirections($row);
            }
        }
    }
}
