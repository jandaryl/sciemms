<?php

namespace Tests\Unit;

use Tests;
use Tests\Testcase;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    // public function setup(UserRepository $user)
    // {
    //     parent::setup();

    //     $this->user = $user;
    // }

    // /** @test */
    // public function it_will_enable_the_batch_of_users()
    // {
    //     $user1 = make(User::class, ['active' => false]);

    //     $this->user->batchEnable([1]);

    //     $this->assertEquals($user1->active, false);
    // }

    /** @test */
    public function it_just_stub()
    {
        $this->markTestIncomplete();
    }
}
