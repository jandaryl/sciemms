<?php

namespace Tests\Feature;

use Tests;
use Tests\BrowserKitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AccountTest extends BrowserKitTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_see_its_account_panels_when_logged_in()
    {
        $user = $this->user();


        $this->actingAs($user)
             ->visit('/user/account')
             ->see('My Account')
             ->see('My Profile')
             ->see('Edit my profile')
             ->see('Change my password')
             ->see('Delete my account');

    }

    /** @test */
    public function user_can_see_the_my_profile_panel()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/user/account#profile')
             ->see('My Profile')
             ->see($user->avatar)
             ->see($user->name)
             ->see($user->email)
             ->see($user->locale)
             ->see($user->timezone)
             ->see($user->created_at->setTimezone($user->timezone))
             ->see($user->created_at->diffForHumans())
             ->see($user->updated_at->setTimezone($user->timezone))
             ->see($user->updated_at->diffForHumans());
    }

    /** @test */
    public function user_can_see_the_edit_my_profile_panel()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/user/account#edit')
             ->see('Edit my profile')
             ->see($user->name)
             ->see($user->email)
             ->see($user->locale)
             ->see($user->timezone);
    }

    /** @test */
    public function user_can_update_the_edit_my_profile_panel()
    {
        $user = $this->user();

        $this->actingAs($user)
             ->visit('/user/account#edit')
             ->type('Profile successfully updated.', 'name')
             ->type('Updated Email', 'email')
             ->select('en', 'locale')
             ->select('UTC', 'timezone')
             ->press('Update')
             ->seePageIs('/user/account')
             ->see('Profile successfully updated.');
    }


    /**
     * Todo :
     *
     * 1. user_can_change_password_in_change_password_panel
     * 2. user_can_delete_its_own_account_completely
     */

    // /** @test */
    // public function user_can_change_password_in_change_password_panel()
    // {
    //     $user = $this->user(['password' => 'secret']);
    //     $this->actingAs($user)
    //          ->visit('/user/account#password')
    //          ->type('secret', 'old_password')
    //          ->type('Updated Email', 'password')
    //          ->type('Updated Email', 'password_confirmation')
    //          ->press('Update')
    //          ->seePageIs('/user/account')
    //          ->see('Profile successfully updated.');
    // }
    // Error : InvalidArgumentException: Nothing matched the filter [old_password] CSS query provided for [http://thesis.test/user/account].

    // /** @test */
    // public function user_can_delete_its_own_account_completely()
    // {
    //     $user = $this->standardUser();

    //     $this->actingAs($user)
    //          ->visit('/user/account#delete')
    //          ->press('Delete')
    //          ->seePageIs('/')
    //          ->see('Account successfully deleted');
    // }
    // Error : #105 /home/jandaryl/Sites/thesis/vendor/phpunit/phpunit/phpunit(53): PHPUnit\TextUI\Command::main()

}
