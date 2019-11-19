<?php

namespace TalvBansal\ThrottledFailedJobMonitor;

use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use DatabaseMigrations;

    protected function getPackageProviders($app)
    {
        return [FailedThrottledJobsServiceProvider::class];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('cache.default', 'array');
        $app['config']->set('queue.default', 'sync');
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
    }
}
