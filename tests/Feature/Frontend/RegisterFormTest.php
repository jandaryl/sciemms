<?php

namespace Tests\Feature;

use Tests;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterFormTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function new_standard_user_can_sign_up_in_registration_form_then_see_the_email_confirmation_message()
    {
        $this->user(); // To avoid the creation of superadmin user.

        $this->ignoreCaptcha();

        $this->visit('/register')
             ->type('Jan Daryl Galbo', 'name')
             ->type('jandarylgalbo@gmail.com', 'email')
             ->type('secret', 'password')
             ->type('secret', 'password_confirmation')
             ->check('g-recaptcha-response')
             ->press('Register')
             ->seePageIs('/user')
             ->see('Your account will be in limited mode as long as your email remains not confirmed. <a href="' . $this->baseUrl . '/user/confirmation/send">Click here</a> in order to resend mail confirmation.');
    }

    /** @test */
    public function user_will_get_error_message_if_the_email_was_already_registered()
    {
        $user = $this->user();

        $this->ignoreCaptcha();

        $this->visit('/register')
             ->type('Jan Daryl Galbo', 'name')
             ->type($user->email, 'email')
             ->type('secret', 'password')
             ->type('secret', 'password_confirmation')
             ->check('g-recaptcha-response')
             ->press('Register')
             ->seePageIs('/register')
             ->see('The Email has already been taken.');
    }

    /** @test */
    public function user_will_get_error_message_if_the_password_not_matched_in_confirm_password()
    {
        $this->ignoreCaptcha();

        $this->visit('/register')
             ->type('Jan Daryl Galbo', 'name')
             ->type('jandarylgalbo@gmail.com', 'email')
             ->type('secret', 'password')
             ->type('not_secret', 'password_confirmation')
             ->check('g-recaptcha-response')
             ->press('Register')
             ->seePageIs('/register')
             ->see('The Password confirmation does not match.');
    }

    /** @test */
    public function user_will_get_error_message_if_the_password_was_less_than_6_registered()
    {
        $this->ignoreCaptcha();

        $this->visit('/register')
             ->type('Jan Daryl Galbo', 'name')
             ->type('jandarylgalbo@gmail.com', 'email')
             ->type('four', 'password')
             ->type('four', 'password_confirmation')
             ->check('g-recaptcha-response')
             ->press('Register')
             ->seePageIs('/register')
             ->see('The Password must be at least 6 characters.');
    }
}
