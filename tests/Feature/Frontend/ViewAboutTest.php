<?php

namespace Tests\Feature;

use Tests;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewAboutTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function any_type_of_users_can_see_the_about_page()
    {
        $this->visit('/about')
             ->see('About')
             ->see("Hey ! I'm a flash message !");
    }
}
