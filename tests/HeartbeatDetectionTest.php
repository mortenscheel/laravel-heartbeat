<?php

namespace MortenScheel\Heartbeat\Tests;

use MortenScheel\Heartbeat\Heartbeat;
use MortenScheel\Heartbeat\HeartbeatMonitor;

class HeartbeatDetectionTest extends TestCase
{
    /** @var HeartbeatMonitor */
    private $monitor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->monitor = $this->app->make(HeartbeatMonitor::class);
    }


    public function test_only_default_queue_is_monitored()
    {
        $queues = $this->monitor->normalizeQueues()->keys();
        $this->assertContains('default', $queues);
        $this->assertCount(1, $queues);
    }

    public function test_no_heartbeat_are_recorded()
    {
        $heartbeats = $this->monitor->getLatestHeartbeats();
        $this->assertCount(1, $heartbeats);
        $this->assertFalse($heartbeats->first()->wasDetected());
    }

    public function test_heartbeat_can_be_recorded()
    {
        $this->monitor->register('default', 1);
        $heartbeats = $this->monitor->getLatestHeartbeats();
        $this->assertTrue($heartbeats->first()->wasDetected());
    }

    public function test_heartbeats_are_identified_as_unhealthy()
    {
        $this->monitor->register('default', 60);
        $this->assertTrue($this->monitor->getLatestHeartbeats()->first()->isHealthy());
        $this->travel(2)->minutes();
        $this->assertFalse($this->monitor->getLatestHeartbeats()->first()->isHealthy());
    }

    public function test_multiple_queues_can_be_monitored()
    {
        config()->set('heartbeat.queues', [
            'default',
            'low',
            'high',
            'short-threshold'   => 10,
            'shorter-threshold' => 5
        ]);
        $this->monitor->dispatchHeartbeatJobs();
        $this->travel(30)->seconds();
        $unhealthy = $this->monitor->getLatestHeartbeats()->filter(function (Heartbeat $heartbeat) {
            return !$heartbeat->isHealthy();
        });
        $this->assertCount(2, $unhealthy);
    }
}
