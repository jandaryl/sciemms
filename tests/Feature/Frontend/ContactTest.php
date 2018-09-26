<?php

namespace Tests\Feature;

use Tests;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ContactTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function any_type_of_users_can_see_the_contact_page()
    {
        $this->visit('/contact')
             ->see('Contact')
             ->see('Name')
             ->see('Postal code')
             ->see('City')
             ->see('Email')
             ->see('Phone')
             ->see('Message')
             ->see('Send');
    }
}
