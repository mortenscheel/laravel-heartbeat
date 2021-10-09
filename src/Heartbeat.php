<?php

namespace MortenScheel\Heartbeat;

use Carbon\Carbon;

class Heartbeat
{
    /** @var int */
    protected $threshold;
    /** @var string */
    private $queue;
    /** @var \Carbon\Carbon|null */
    private $executed_at;

    public function __construct(
        string $queue,
        int $threshold,
        Carbon $executed_at = null
    ) {
        $this->queue = $queue;
        $this->threshold = $threshold;
        $this->executed_at = $executed_at;
    }

    public function wasDetected(): bool
    {
        return $this->executed_at !== null;
    }

    public function isHealthy(): bool
    {
        return $this->wasDetected() &&
            $this->executed_at->diffInSeconds() <= $this->threshold;
    }

    public function diffForHumans(): string
    {
        return $this->wasDetected() ? $this->executed_at->diffForHumans() : 'Not found';
    }

    public function maxTimeForHumans(): string
    {
        return now()->subSeconds($this->threshold)->diffForHumans(null, true);
    }

    public function getQueue(): string
    {
        return $this->queue;
    }

    public function getExecutedAt(): ?Carbon
    {
        return $this->executed_at;
    }
}
