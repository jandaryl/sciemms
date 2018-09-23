<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\FormSubmission;
use App\Utils\RequestSearchQuery;
use App\Repositories\Contracts\FormSubmissionRepository;

class FormSubmissionController extends BackendController
{
    /**
     * @var FormSubmissionRepository
     */
    protected $formSubmissions;

    /**
     * Create a new controller instance.
     *
     * @param FormSubmissionRepository $formSubmissions
     */
    public function __construct(FormSubmissionRepository $formSubmissions)
    {
        $this->formSubmissions = $formSubmissions;
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
     * Show the Form Submission
     *
     * @param FormSubmission $form_submission
     * @return FormSubmission
     */
    public function show(FormSubmission $form_submission)
    {
        return $form_submission;
    }

    /**
     * Delete the Form Submission
     *
     * @param FormSubmission $form_submission
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(FormSubmission $form_submission, Request $request)
    {
        $this->canDeleteFormSubmissions();

        $this->formSubmissions->destroy($form_submission);

        return $this->redirectResponse($request, __('alerts.backend.form_submissions.deleted'));
    }

    /**
     * Batch actions from the form submissions selected.
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
                return $this->deleteFormSubmissions($request, $ids);
                break;
        }

        return $this->redirectResponse($request, __('alerts.backend.actions.invalid'), 'error');
    }

    /**
     * Search the request data.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function requestData(Request $request)
    {
        $requestSearchQuery = new RequestSearchQuery($request, $this->formSubmissions->query(), [
            'data',
        ]);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export([
                'type',
                'data',
                'created_at',
                'updated_at',
            ],
                [
                    __('validation.attributes.form_type'),
                    __('validation.attributes.form_data'),
                    __('labels.created_at'),
                    __('labels.updated_at'),
                ],
                'submissions');
        }

        return $requestSearchQuery->result([
            'id',
            'type',
            'data',
            'created_at',
            'updated_at',
        ]);
    }


    /**
     * Delete the form submissions from request.
     *
     * @param Request $request
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deleteFormSubmissions(Request $request, $ids)
    {
        $this->canDeleteFormSubmissions();

        $this->formSubmissions->batchDestroy($ids);

        return $this->redirectResponse($request, __('alerts.backend.form_submissions.bulk_destroyed'));
    }

    /**
     *  Check if user can delete the form submissions.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canDeleteFormSubmissions(): void
    {
        $this->authorize('delete form_submissions');
    }
}
