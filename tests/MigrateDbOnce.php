<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;

trait MigrateDbOnce
{
    /**
     * If true, setup has run at least once.
     * @var bool
     */
    protected static $setUpHasRunOnce = false;

    /**
     * After the first run of setUp "migrate:fresh --seed".
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        if (!static::$setUpHasRunOnce) {
            Artisan::call('migrate:fresh', ['--seed' => true, '--env' => 'testing']);
            static::$setUpHasRunOnce = true;
        }
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     *
     * @throws \Mockery\Exception\InvalidCountException
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }
}
