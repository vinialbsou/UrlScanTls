<?php

namespace Tests;

use Illuminate\Support\Facades\Queue;

abstract class TestCaseWithFakeQueue extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        //https://laravel.com/docs/9.x/mocking#queue-fake
        Queue::fake();
    }
}
