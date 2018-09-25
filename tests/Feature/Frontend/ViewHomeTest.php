<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewHomeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_users_can_see_the_home_page()
    {
        $response = $this->get('/');

        $response->assertSee('SCIEMMS')
                 ->assertSee('About')
                 ->assertSee('Announcement')
                 ->assertSee('Contact')
                 ->assertSee('Login')
                 ->assertSee('Register');
    }
}
