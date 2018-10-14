<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setup()
    {
        parent::setup();

        $this->disableExceptionHandling();
    }

    protected function signIn($user = null)
    {
        $this->avoidBeingSuperAdmin();

        $user = $user ?: create(User::class);

        $this->actingAs($user);

        return $this;
    }

    // protected function signInAdmin($user = null)
    // {
    //     $user = $user ?: create(User::class);
    //     $role = create(Role::class, ['name' => 'Admin']);
    //     $permissions = create(Permission::class);
    //     // dd($role);
    //     $adminRole = $role->permissions()->save($permissions);
    //     $adminUser = $user->roles()->permissions()->save($permissions);

    //     $this->actingAs($adminUser);

    //     return $this;
    // }

    protected function signInSuperAdmin($user = null)
    {
        $superAdmin = $user ?: create(User::class);

        $this->actingAs($superAdmin);

        return $this;
    }

    protected function avoidBeingSuperAdmin()
    {
        /**
         * The first user that will be created will become super admin.
         */
        return create(User::class);
    }

    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
        $this->app->instance(ExceptionHandler::class, new class() extends Handler {
            public function __construct()
            {
            }

            public function report(\Exception $e)
            {
            }

            public function render($request, \Exception $e)
            {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

        return $this;
    }
}
