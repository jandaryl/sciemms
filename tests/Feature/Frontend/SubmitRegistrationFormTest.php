<?php

namespace Tests\Feature;

use Tests;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubmitRegistrationFormTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function new_user_can_sign_up_in_registration_form_then_see_the_confirmation_message()
    {
        $this->ignoreCaptcha();
        $this->user();

        $this->visit('/register')
             ->type('Jan Daryl Galbo', 'name')
             ->type('jandarylgalbo@gmail.com', 'email')
             ->type('secret', 'password')
             ->type('secret', 'password_confirmation')
             ->check('g-recaptcha-response')
             ->press('Register')
             ->seePageIs('/user')
             ->see('Your account will be in limited mode as long as your email remains not confirmed. <a href="http://thesis.test/user/confirmation/send">Click here</a> in order to resend mail confirmation.');
    }

}
