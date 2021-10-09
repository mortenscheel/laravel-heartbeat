<?php

namespace MortenScheel\Heartbeat;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use MortenScheel\Heartbeat\Jobs\HeartbeatJob;
use MortenScheel\Heartbeat\Mail\UnhealthyHeartbeatMail;
use function is_string;

class HeartbeatMonitor
{
    public function isEnabled(): bool
    {
        return config('heartbeat.enabled');
    }

    public function register(string $queue, int $threshold): void
    {
        Cache::put(
            $this->getCacheKey($queue),
            new Heartbeat($queue, $threshold, now()),
            now()->addHours(24)
        );
    }

    /**
     * @return \Illuminate\Support\Collection|array<string, int>
     */
    public function normalizeQueues(): Collection
    {
        return collect(config('heartbeat.queues'))->mapWithKeys(function ($value, $key) {
            if (is_string($key)) {
                return [$key => $value];
            }
            return [$value => config('heartbeat.default_threshold')];
        });
    }

    public function dispatchHeartbeatJobs(): void
    {
        $this->normalizeQueues()->each(function (int $threshold, string $queue) {
            HeartbeatJob::dispatch($queue, $threshold);
        });
    }

    /**
     * @return \Illuminate\Support\Collection|\MortenScheel\Heartbeat\Heartbeat[]
     */
    public function getLatestHeartbeats(): Collection
    {
        return $this->normalizeQueues()->map(function (int $threshold, string $queue) {
            return Cache::get($this->getCacheKey($queue)) ?? new Heartbeat($queue, $threshold);
        })->values();
    }

    public function warnRecipients(Collection $unhealthy): void
    {
        $queues = $unhealthy->map->getQueue()->toArray();
        $key = $this->getCacheKey('previous_warning');
        if (($previous_warning = Cache::get($key)) && $previous_warning === $queues) {
            return;
        }
        Mail::to(config('heartbeat.recipients'))->send(new UnhealthyHeartbeatMail($unhealthy));
        Cache::put($key, $queues, now()->addMinutes(config('heartbeat.deduplication_minutes')));
    }

    private function getCacheKey(string $name): string
    {
        return sprintf('%s:%s', config('heartbeat.cache_prefix'), $name);
    }
}
