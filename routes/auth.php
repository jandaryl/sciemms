<?php

Route::group(
    ['namespace' => 'Auth'],
    function ()
    {
        if (config('account.can_register'))
        {
            Route::get('register',                              'RegisterController@showRegistrationForm')->name('register');
            Route::post('register',                             'RegisterController@register');
        }

        Route::get('login',                                     'LoginController@showLoginForm')->name('login');
        Route::post('login',                                    'LoginController@login');
        Route::get('logout',                                    'LoginController@logout')->name('logout');
        Route::get('login/{provider}',                          'LoginController@redirectToProvider')->name('social.login');
        Route::get('login/{provider}/callback',                 'LoginController@handleProviderCallback')->name('social.callback');
        Route::get(config('app.admin_path').'/login',           'LoginController@showAdminLoginForm')->name('admin.login');
        Route::get(config('app.admin_path').'/logout',          'LoginController@adminLogout')->name('admin.logout');

        Route::get(config('app.admin_path').'/password/reset',  'ForgotPasswordController@showAdminLinkRequestForm')->name('admin.password.request');
        Route::get('password/reset',                            'ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email',                           'ForgotPasswordController@sendResetLinkEmail')->name('password.email');

        Route::get('password/reset/{token}',                    'ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset',                           'ResetPasswordController@reset');

    }
);

Route::group(
    [
        'namespace'  => 'User',
        'prefix'     => 'user',
        'as'         => 'user.',
        'middleware' => ['auth'],
    ],
    function ()
    {

        Route::get('/',                                         'UserController@index')->name('home');

        Route::get('account',                                   'AccountController@index')->name('account');
        Route::patch('account/update',                          'AccountController@update')->name('account.update');
        Route::patch('password/change',                         'AccountController@changePassword')->name('password.change');
        Route::get('confirmation/send',                         'AccountController@sendConfirmation')->name('confirmation.send');
        Route::get('email/confirm/{token}',                     'AccountController@confirmEmail')->name('email.confirm');

        if (config('account.can_delete'))
        {
            Route::delete('account/delete', 'AccountController@delete')->name('account.delete');
        }
    }
);
