<?php

namespace Tests\Feature;

use Tests;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubmitLoginFormTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function standard_user_can_sign_in_the_login_form_then_see_email_confirmation_message_if_not_yet_confirmed()
    {
        $standard_user = $this->standardUser([['password' => 'secret']]);

        $this->visit('/login')
             ->type($standard_user->email, 'email')
             ->type('secret', 'password')
             ->press('Login')
             ->seePageIs('/user');

        if ($standard_user->isConfirmed()) {
            $this->see('Your account will be in limited mode as long as your email remains not confirmed. <a href="http://thesis.test/user/confirmation/send">Click here</a> in order to resend mail confirmation.');
        }
    }

    /** @test */
    public function super_admin_user_can_sign_in_the_login_form_then_see_dashboard()
    {
        $super_admin_user = $this->superAdminUser();

        $this->visit('/login')
             ->type($super_admin_user->email, 'email')
             ->type('secret', 'password')
             ->press('Login')
             ->seePageIs('/admin')
             ->see('Dashboard');
    }

    /** @test */
    public function users_can_see_and_click_the_forget_password_link()
    {
        $this->visit('/login')
             ->seeLink('Forget password ?')
             ->click('Forget password ?')
             ->seePageIs('/password/reset');
    }

    /** @test */
    public function any_type_of_users_can_send_reset_password_link_by_email_then_see_success_message()
    {
        $any_type_user = $this->user();

        $this->visit('/password/reset')
             ->type($any_type_user->email, 'email')
             ->press('Send reset password link')
             ->seePageIs('/password/reset')
             ->see('We have e-mailed your password reset link!');
    }
}
