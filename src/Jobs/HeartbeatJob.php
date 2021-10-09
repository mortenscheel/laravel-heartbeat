<?php

namespace MortenScheel\Heartbeat\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MortenScheel\Heartbeat\HeartbeatMonitor;

class HeartbeatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int */
    protected $threshold;

    /**
     * Create a new job instance.
     * @return void
     */
    public function __construct(string $queue, int $threshold)
    {
        $this->queue = $queue;
        $this->threshold = $threshold;
    }


    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        (new HeartbeatMonitor)->register($this->queue, $this->threshold);
    }
}
