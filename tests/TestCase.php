<?php

namespace MortenScheel\Heartbeat\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return ['MortenScheel\Heartbeat\HeartbeatServiceProvider'];
    }
}
