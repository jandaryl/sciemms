<?php

Route::get('index/search',              'AjaxController@search')->name('search');
Route::get('routes/search',             'AjaxController@routesSearch')->name('routes.search');
Route::get('tags/search',               'AjaxController@tagsSearch')->name('tags.search');
Route::post('images/upload',            'AjaxController@imageUpload')->name('images.upload');

if (config('features.form-settings'))
{
    Route::group(
        ['middleware' => ['can:view form_settings']],
        function ()
        {
            Route::get('form_settings/form_types',          'FormSettingController@getFormTypes')->name('form_settings.get_form_types');
            Route::get('form_settings/search',              'FormSettingController@search')->name('form_settings.search');
            Route::get('form_settings/{form_setting}/show', 'FormSettingController@show')->name('form_settings.show');

            Route::resource('form_settings', 'FormSettingController', [
                'only' => ['store', 'update', 'destroy'],
            ]);
        }
    );
}

if (config('features.form-submissions'))
{
    Route::group(
        ['middleware' => ['can:view form_submissions']],
        function ()
        {
            Route::get('form_submissions/counter',                  'DashboardController@getFormSubmissionCounter')->name('form_submissions.counter');
            Route::get('form_submissions/search',                   'FormSubmissionController@search')->name('form_submissions.search');
            Route::get('form_submissions/{form_submission}/show',   'FormSubmissionController@show')->name('form_submissions.show');
            Route::post('form_submissions/batch_action',            'FormSubmissionController@batchAction')->name('form_submissions.batch_action');

            Route::resource('form_submissions', 'FormSubmissionController', [
                'only' => ['destroy'],
            ]);
        }
    );
}


Route::group(
    ['middleware' => ['can:view users']],
    function ()
    {
        Route::get('users/active_counter',                      'DashboardController@getActiveUserCounter')->name('users.active.counter');
        Route::get('users/roles',                               'UserController@getRoles')->name('users.get_roles');
        Route::get('users/search',                              'UserController@search')->name('users.search');
        Route::get('users/{user}/show',                         'UserController@show')->name('users.show');
        Route::get('users/{user}/impersonate',                  'UserController@impersonate')->name('users.impersonate');
        Route::post('users/batch_action',                       'UserController@batchAction')->name('users.batch_action');
        Route::post('users/{user}/active',                      'UserController@activeToggle')->name('users.active');

        Route::resource('users', 'UserController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

Route::group(
    ['middleware' => ['can:view roles']],
    function ()
    {
        Route::get('roles/permissions',                         'RoleController@getPermissions')->name('roles.get_permissions');
        Route::get('roles/search',                              'RoleController@search')->name('roles.search');
        Route::get('roles/{role}/show',                         'RoleController@show')->name('roles.show');

        Route::resource('roles', 'RoleController', [
            'only' => ['store', 'update', 'destroy'],
        ]);
    }
);

if (config('features.metas'))
{
    Route::group(
        ['middleware' => ['can:view metas']],
        function ()
        {
            Route::get('metas/search',                              'MetaController@search')->name('metas.search');
            Route::get('metas/{meta}/show',                         'MetaController@show')->name('metas.show');
            Route::post('metas/batch_action',                       'MetaController@batchAction')->name('metas.batch_action');

            Route::resource('metas', 'MetaController', [
                'only' => ['store', 'update', 'destroy'],
            ]);
        }
    );
}

if (config('features.redirection'))
{
    Route::group(
        ['middleware' => ['can:view redirections']],
        function ()
        {
            Route::get('redirections/redirection_types',        'RedirectionController@getRedirectionTypes')->name('redirections.get_redirection_types');
            Route::get('redirections/search',                   'RedirectionController@search')->name('redirections.search');
            Route::get('redirections/{redirection}/show',       'RedirectionController@show')->name('redirections.show');
            Route::post('redirections/batch_action',            'RedirectionController@batchAction')->name('redirections.batch_action');
            Route::post('redirections/{redirection}/active',    'RedirectionController@activeToggle')->name('redirections.active');
            Route::post('redirections/import',                  'RedirectionController@import')->name('redirections.import');

            Route::resource('redirections', 'RedirectionController', [
                'only' => ['store', 'update', 'destroy'],
            ]);
        }
    );
}

if (config('blog.enabled')) {
    Route::group(
        ['middleware' => ['can:view own posts']],
        function ()
        {
            Route::get('posts/draft_counter',               'DashboardController@getDraftPostCounter')->name('posts.draft.counter');
            Route::get('posts/pending_counter',             'DashboardController@getPendingPostCounter')->name('posts.pending.counter');
            Route::get('posts/published_counter',           'DashboardController@getPublishedPostCounter')->name('posts.published.counter');
            Route::get('posts/latest',                      'PostController@getLastestPosts')->name('posts.latest');
            Route::get('posts/search',                      'PostController@search')->name('posts.search');
            Route::get('posts/{post}/show',                 'PostController@show')->name('posts.show');
            Route::post('posts/batch_action',               'PostController@batchAction')->name('posts.batch_action');
            Route::post('posts/{post}/pinned',              'PostController@pinToggle')->name('posts.pinned');
            Route::post('posts/{post}/promoted',            'PostController@promoteToggle')->name('posts.promoted');

            Route::resource('posts', 'PostController', [
                'only' => ['store', 'update', 'destroy'],
            ]);
        }
    );
}

Route::get('/{vue_capture?}',       'BackendController@index')->where('vue_capture', '[\/\w\.-]*')->name('home');
