<?php

namespace App\Http\Controllers\Backend;

use App\Models\FormSetting;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use App\Http\Requests\StoreFormSettingRequest;
use App\Http\Requests\UpdateFormSettingRequest;
use App\Repositories\Contracts\FormSettingRepository;

class FormSettingController extends BackendController
{
    /**
     * @var FormSettingRepository
     */
    protected $formSettings;

    /**
     * Create a new controller instance.
     *
     * @param FormSettingRepository $formSettings
     */
    public function __construct(FormSettingRepository $formSettings)
    {
        $this->formSettings = $formSettings;
    }


    /**
     * Search the data from the form setting.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function search(Request $request)
    {
        return $this->requestData($request);
    }


    /**
     * Get the form types.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFormTypes()
    {
        $formTypes = collect(config('forms'));

        $this->formTypeTransform($formTypes);

        return $formTypes;
    }


    /**
     * Show the form setting.
     *
     * @param FormSetting $form_setting
     * @return FormSetting
     */
    public function show(FormSetting $form_setting)
    {
        return $form_setting;
    }


    /**
     * Store the form setting request to the database.
     *
     * @param StoreFormSettingRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreFormSettingRequest $request)
    {
        $this->canCreateFormSettings();

        $this->formSettings->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.form_settings.created'));
    }


    /**
     * Update the form setting  request from the database.
     *
     * @param FormSetting $formSetting
     * @param UpdateFormSettingRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(FormSetting $formSetting, UpdateFormSettingRequest $request)
    {
        $this->canEditFormSettings();

        $this->formSettings->update($formSetting, $request->input());

        return $this->redirectResponse($request, __('alerts.backend.form_settings.updated'));
    }


    /**
     * Delete the form setting from the database.
     *
     * @param FormSetting $formSetting
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(FormSetting $formSetting, Request $request)
    {
        $this->canDeleteFormSettings();

        $this->formSettings->destroy($formSetting);

        return $this->redirectResponse($request, __('alerts.backend.form_settings.deleted'));
    }

    /**
     * Search the request data.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function requestData(Request $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $requestSearchQuery = new RequestSearchQuery($request, $this->formSettings->query());

        return $requestSearchQuery->result([
            'id',
            'name',
            'recipients',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * Check if the user can create form settings.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canCreateFormSettings(): void
    {
        $this->authorize('create form_settings');
    }

    /**
     * Check if the user can edit form settings.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canEditFormSettings(): void
    {
        $this->authorize('edit form_settings');
    }

    /**
     * Check if the user can delete form settings.
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function canDeleteFormSettings(): void
    {
        $this->authorize('delete form_settings');
    }


    /**
     * Transform the form types in its display name.
     *
     * @param $formTypes
     */
    public function formTypeTransform($formTypes): void
    {
        $formTypes->transform(function ($item) {
            return __($item['display_name']);
        });
    }
}
