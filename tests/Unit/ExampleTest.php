<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     */
    public function testBasicTest()
    {
        $this->signIn();
        // $this->signInAdmin();
        $this->signInSuperAdmin();

        $this->assertTrue(true);
    }
}
