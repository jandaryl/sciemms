<?php

namespace Tests;

use Arcanedev\NoCaptcha\Facades\NoCaptcha;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class BrowserKitTestCase extends BaseTestCase
{
    use CreatesApplication;

    public $baseUrl = 'https://sciemms.frb.io';

    protected function user()
    {
        return factory(\App\Models\User::class)->create();
    }

    protected function standardUser()
    {
        $this->user();

        return $this->user();
    }

    protected function superAdminUser()
    {
        return $this->user();
    }

    protected function ignoreCaptcha($name = 'g-recaptcha-response')
    {
        NoCaptcha::shouldReceive('display')
            ->andReturn('<input type="checkbox" value="yes" name="' . $name . '">');

        NoCaptcha::shouldReceive('script')
            ->andReturn('<script src="captcha.js"></script>');

        NoCaptcha::shouldReceive('verify')
            ->andReturn(true);
    }
}
