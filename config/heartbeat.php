<?php
return [
    /*
     * When enabled, Laravel's sceduler will run the heartbeat:monitor
     * command every minute, which will:
     * 1. Dispatch heartbeat jobs on the configured queues.
     * 2. Send an email to the configured recipients if a
     *    queue exceeds the maximum time between heartbeats.
     */
    'enabled'               => env('HEARTBEAT_ENABLED', false),
    /*
     * Maximum seconds between heartbeats, unless overridden.
     */
    'default_threshold'     => 120,
    /*
     * Which queues to monitor.
     * To set a custom threshold, use the format
     * 'queue_name' => maximum_seconds,
     */
    'queues'                => [
        'default',
    ],
    /*
     * Recipients will receive an "Unhealthy heartbeat warning" e-mail
     * when one or more queues exceed the configured threshold
     */
    'recipients'            => [
    ],
    /*
     * Minimum time between identical e-mail warnings
     */
    'deduplication_minutes' => 1440,
    /*
     * Heartbeats are stored in the application cache (for 24 hours) with this cache prefix
     */
    'cache_prefix'          => 'heartbeat',
];
