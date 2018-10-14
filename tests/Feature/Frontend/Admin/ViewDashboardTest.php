<?php

namespace Tests\Feature;

use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewDashboardTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_see_the_admin_login_page_if_guest_user_will_try_to_access_using_url()
    {
        $this->visit('/admin/login')
             ->see('Login');
    }
}
