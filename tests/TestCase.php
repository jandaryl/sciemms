<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // protected $user;
    // protected $role;

    // public function __construct()
    // {
    //     $this->user = new User;
    //     $this->role = new Role;
    // }

    public function signIn($user = null)
    {
        $this->avoidBeingSuperAdmin();

        $user = $user ?: create(User::class);

        $this->actingAs($user);

        return $this;
    }

    // public function signInAdmin($user = null, $role = null)
    // {
    //     $user = $this->user->create($user) ?: create(User::class);

    //     $role = $this->role->create($role) ?: create(Role::class);

    //     foreach ($this->permissions as $name) {
    //         $role->permissions()->create(['name' => $name]);
    //     }

    //     $admin = $user->roles()->save($role);

    //     $this->actingAs($admin);

    //     return $this;
    // }

    public function signInSuperAdmin($user = null)
    {
        $superAdmin = $user ?: create(User::class);

        $this->actingAs($superAdmin);

        return $this;
    }

    public function avoidBeingSuperAdmin()
    {
        return create(User::class);
    }

    // public function permissions()
    // {
    //     return [
    //         'access backend',
    //         'view posts',
    //         'create posts',
    //         'edit posts',
    //         'delete posts',
    //         'publish posts',
    //         'view form_submissions',
    //         'delete form_submissions',
    //         'view users',
    //         'create users',
    //         'edit users',
    //         'delete users',
    //     ];
    // }
}
