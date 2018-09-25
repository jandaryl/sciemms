<?php

namespace Tests\Feature;

use Tests;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewNavBarTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function users_can_see_and_click_the_home_link()
    {
        $this->visit('/')
             ->seeLink('SCIEMMS')
             ->click('SCIEMMS')
             ->seePageIs('/');
    }

    /** @test */
    public function users_can_see_and_click_the_about_link()
    {
        $this->visit('/')
             ->seeLink('About')
             ->click('About')
             ->seePageIs('/about');
    }


    /** @test */
    public function users_can_see_and_click_the_announcement_link()
    {
        $this->visit('/')
             ->seeLink('Announcement')
             ->click('Announcement')
             ->seePageIs('/announcement');
    }

    /** @test */
    public function users_can_see_and_click_the_contact_link()
    {
        $this->visit('/')
             ->seeLink('Contact')
             ->click('Contact')
             ->seePageIs('/contact');
    }

    /** @test */
    public function users_can_see_and_click_the_login_link()
    {
        $this->visit('/')
             ->seeLink('Login')
             ->click('Login')
             ->seePageIs('/login');
    }

    /** @test */
    public function users_can_see_and_click_the_register_link()
    {
        $this->visit('/')
             ->seeLink('Register')
             ->click('Register')
             ->seePageIs('/register');
    }

    /** @test */
    public function users_can_see_and_click_the_logout_link_when_logged_in()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/')
             ->seeLink('Logout')
             ->click('Logout')
             ->seePageIs('/');
    }

    /** @test */
    public function users_can_see_and_click_the_my_space_link_when_logged_in()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/')
             ->seeLink('My space')
             ->click('My space')
             ->seePageIs('/user');
    }

    /** @test */
    public function users_can_see_and_click_the_my_account_link_when_logged_in()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/')
             ->seeLink('My account')
             ->click('My account')
             ->seePageIs('/user/account');
    }

        /** @test */
    public function users_can_see_and_click_the_administration_link_if_it_has_permission_when_logged_in()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/');

        if ($user->canAccessBackend()) {
            $this->seeLink('Administration')
                 ->click('Administration')
                 ->seePageIs('/admin');
        }
    }

}
