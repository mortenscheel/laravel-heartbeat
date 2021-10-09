# Heartbeat

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

A simple way to detect when your queue workers are hanging or can't keep up.

## Installation

Via Composer

``` bash
$ composer require mortenscheel/laravel-heartbeat
```

## Configuration

Publish config
``` bash
$ php artisan vendor:publish --tag=heartbeat-config
```

Default configuration
```php
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

```

## Usage
Enable heartbeat detection by adding the following variable to `.env`:
```
HEARTBEAT_ENABLED=true
```
If Laravel's Scheduler is running via cron, the `heartbeat:monitor` command will run automatically every minute.

To see the current status of queue heartbeats, you can run `heartbeat:monitor` manually in the console:
```bash
$ php artisan heartbeat:monitor
+---------+----------------+-------------+
| Queue   | Last heartbeat | Max allowed |
+---------+----------------+-------------+
| default | 1 minute ago   | 2 minutes   |
| high    | 1 minute ago   | 2 minutes   |
| low     | 1 minute ago   | 3 minutes   |
| encode  | 2 minutes ago  | 30 minutes  |
+---------+----------------+-------------+
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email morten@mortenscheel.com instead of using the issue tracker.

## Credits

- [Morten Scheel][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/mortenscheel/laravel-heartbeat.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/mortenscheel/laravel-heartbeat.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/mortenscheel/laravel-heartbeat
[link-downloads]: https://packagist.org/packages/mortenscheel/laravel-heartbeat
[link-author]: https://github.com/mortenscheel
[link-contributors]: ../../contributors
