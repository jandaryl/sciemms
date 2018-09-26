<?php

namespace Tests\Feature;

use Tests;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class HomeTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_users_can_see_the_home_page()
    {
        $this->visit('/')
             ->see('SCIEMMS')
             ->see('Home');
    }

    /** @test */
    public function users_cannot_see_the_login_and_register_when_logged_in()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/')
             ->dontSee('Login')
             ->dontSee('Register');
    }

    /** @test */
    public function users_can_see_a_logout_when_logged_in()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/')
             ->see('Logout');
    }

    /** @test */
    public function users_can_see_a_its_name_when_logged_in()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/')
             ->see($user->name);
    }

    /** @test */
    public function users_can_see_a_my_space_when_logged_in()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/')
             ->see('My space');
    }

    /** @test */
    public function users_can_see_a_my_account_when_logged_in()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/')
             ->see('My account');
    }

    /** @test */
    public function users_can_see_a_adminitration_if_it_has_permission_when_logged_in()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/');

        if ($user->canAccessBackend()) {
            $this->see('Administration');
        }
    }
}
