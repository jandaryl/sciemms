<?php

namespace Tests;

use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class BrowserKitTestCase extends BaseTestCase
{
    use CreatesApplication;

    public $baseUrl = 'http://thesis.test';

    protected function user()
    {
        return factory(\App\Models\User::class)->create();
    }
}
