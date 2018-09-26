<?php

namespace Tests\Feature;

use Tests;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MySpaceTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_see_the_my_space_information_when_logged_in()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/user')
             ->see('Dashboard')
             ->see($user->name)
             ->see($user->email)
             ->see($user->created_at->formatLocalized('%A %d %B %Y'));
    }

    /** @test */
    public function user_can_see_the_links_of_account_then_administration_if_it_has_permission_to_access_it()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/user')
             ->seeLink('My account');

        if ($user->canAccessBackend()) {
            $this->seeLink('Administration');
        }
    }

}
