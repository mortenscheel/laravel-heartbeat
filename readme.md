# Heartbeat

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

A simple way to detect when your queue workers are hanging or can't keep up.

## Installation

Via Composer

``` bash
$ composer require mortenscheel/laravel-heartbeat
```

Publish config
``` bash
$ php artisan vendor:publish --tag=heartbeat-config
```

Update `config/heartbeat.php` with the queues you want to monitor, and add your e-mail address to receive an email when one or more of your queues exceed the maximum time between heartbeats.

## Usage
Enable heartbeat detection by adding the following variable to `.env`:
```
HEARTBEAT_ENABLED=true
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

[ico-version]: https://img.shields.io/packagist/v/mortenscheel/heartbeat.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/mortenscheel/heartbeat.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/mortenscheel/heartbeat/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/mortenscheel/heartbeat
[link-downloads]: https://packagist.org/packages/mortenscheel/heartbeat
[link-travis]: https://travis-ci.org/mortenscheel/heartbeat
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/mortenscheel
[link-contributors]: ../../contributors
