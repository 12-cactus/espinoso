<?php namespace Tests;

use Illuminate\Support\Facades\Artisan;

abstract class DBTestCase extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        Artisan::call("migrate");
    }

    protected function tearDown()
    {
        Artisan::call("migrate:reset");

        parent::tearDown();
    }
}
