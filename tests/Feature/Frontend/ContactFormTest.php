<?php

namespace Tests\Feature;

use Tests;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ContactFormTest  extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function any_users_can_send_in_contact_form()
    {
        $this->ignoreCaptcha();

        $this->visit('/contact')
             ->type('Jan Daryl Galbo', 'name')
             ->type('6121', 'postal_code')
             ->type('cadiz', 'city')
             ->type('jandarylgalbo@gmail.com', 'email')
             ->type('+639198425028', 'phone')
             ->type('I need help!!', 'message')
             ->check('g-recaptcha-response')
             ->press('Send')
             ->seePageIs('/contact-sent')
             ->see('Message sent');
    }

}
