<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Tests;
use Tests\BrowserKitTestCase;

class LoginFormTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function standard_user_can_sign_in_the_login_form()
    {
        $standard_user = $this->standardUser(['password' => 'secret']);

        $this->visit('/login')
             ->type($standard_user->email, 'email')
             ->type('secret', 'password')
             ->press('Login')
             ->seePageIs('/user')
             ->see('My account');
    }

    /** @test */
    public function super_admin_user_can_sign_in_the_login_form()
    {
        $super_admin_user = $this->superAdminUser(['password' => 'secret']);

        $this->visit('/login')
             ->type($super_admin_user->email, 'email')
             ->type('secret', 'password')
             ->press('Login')
             ->seePageIs('/admin')
             ->see('Dashboard');
    }

    /** @test */
    public function user_will_notify_with_error_message_if_the_credentials_was_invalid()
    {
        $user = $this->user(['password' => 'secret']);

        $this->visit('/login')
             ->type($user->email, 'email')
             ->type('wrong_password', 'password')
             ->press('Login')
             ->seePageIs('/login')
             ->see('These credentials do not match our records.');
    }

    /** @test */
    public function users_can_see_and_click_the_forget_password_link()
    {
        $this->visit('/login')
             ->seeLink('Forget password ?')
             ->click('Forget password ?')
             ->seePageIs('/password/reset')
             ->see('Send reset password link');
    }

    /** @test */
    public function any_type_of_users_can_send_reset_password_link_by_email()
    {
        $any_type_user = $this->user();

        $this->visit('/password/reset')
             ->type($any_type_user->email, 'email')
             ->press('Send reset password link')
             ->seePageIs('/password/reset')
             ->see('We have e-mailed your password reset link!');
    }

    /** @test */
    public function user_will_notify_with_error_message_if_the_email_was_not_found()
    {
        $user = $this->user();

        $this->visit('/password/reset')
             ->type('not_found_email@test.com', 'email')
             ->press('Send reset password link')
             ->seePageIs('/password/reset')
             ->see("We can't find a user with that e-mail address.");
    }

    /**
     * Todo :
     *
     * 1. users_can_be_remembered_if_they_check_the_remember_me
     * 2. users_cannot_be_remembered_if_they_uncheck_the_remember_me
     *
     * Error : The isRemembered logic in user model.
     */

    // /** @test */
    // public function users_can_be_remembered_if_they_check_the_remember_me()
    // {
    //     $any_type_user = $this->user(['password' => 'secret']);

    //     $this->visit('/login')
    //          ->type($any_type_user->email, 'email')
    //          ->type('secret', 'password')
    //          ->check('remember')
    //          ->press('Login')
    //          ->seePageIs('/admin')
    //          ->see('Dashboard')
    //          ->assertTrue( ! $any_type_user->isRemembered()); // Not yet tested in remember but it is working.
    // }

    // /** @test */
    // public function users_cannot_be_remembered_if_they_uncheck_the_remember_me()
    // {
    //     $any_type_user = $this->user(['password' => 'secret']);

    //     $this->visit('/login')
    //          ->type($any_type_user->email, 'email')
    //          ->type('secret', 'password')
    //          ->press('Login')
    //          ->seePageIs('/admin')
    //          ->see('Dashboard')
    //          ->assertFalse($any_type_user->isRemembered()); // Not yet tested in remember but it is working.
    // }
}
