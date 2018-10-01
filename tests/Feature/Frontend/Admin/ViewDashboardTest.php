<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewDashboardTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_see_the_admin_login_page_if_guest_user_will_try_to_access_using_url()
    {
        $response = $this->get('/admin/login');

        $response->assertSee('Login');
    }
}
