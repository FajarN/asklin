<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $appKey = config('app.mobile_app_key');
        if ($appKey) {
            $this->withHeader('X-App-Key', $appKey);
        }
    }
}
