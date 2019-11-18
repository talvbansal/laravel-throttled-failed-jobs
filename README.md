# Throttled notifications for failed jobs within Laravel 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/talvbansal/laravel-throttled-failed-jobs.svg?style=flat-square)](https://packagist.org/packages/talvbansal/laravel-throttled-failed-jobs)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/talvbansal/laravel-throttled-failed-jobs/master.svg?style=flat-square)](https://travis-ci.org/talvbansal/laravel-throttled-failed-jobs)
[![StyleCI](https://styleci.io/repos/222522882/shield)](https://styleci.io/repos/222522882)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/talvbansal/laravel-throttled-failed-jobs.svg?style=flat-square)](https://scrutinizer-ci.com/g/talvbansal/laravel-throttled-failed-jobs)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/talvbansal/laravel-throttled-failed-jobs/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/talvbansal/laravel-throttled-failed-jobs/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/talvbansal/laravel-throttled-failed-jobs.svg?style=flat-square)](https://packagist.org/packages/talvbansal/laravel-throttled-failed-jobs)

This package sends notifications if a queued job fails. 
However sometimes there are jobs that will trigger far too many notifications when failing and therefore throttling for those would be desirable. 
Out of the box it can send a notification via mail, Slack, and Microsoft Teams. It leverages Laravel's native notification system.

This package is heavily based on [Laravel failed job monitor](https://github.com/spatie/laravel-failed-job-monitor) by [Spatie](https://spatie.be/)

## Installation

You can install the package via composer:

``` bash
composer require talvbansal/laravel-throttled-failed-jobs
```

The service provider will automatically be registered.

Next, you must publish the config file:

```bash
php artisan vendor:publish --provider="TalvBansal\ThrottledFailedJobMonitor\FailedThrottledJobsServiceProvider"
```

This is the contents of the default configuration file.  Here you can specify the notifiable to which the notifications should be sent. The default notifiable will use the variables specified in this config file.

```php
return [

    /**
     * The notification that will be sent when a job fails.
     */
    'notification' => \Spatie\FailedJobMonitor\Notification::class,

    /**
     * The notifiable to which the notification will be sent. The default
     * notifiable will use the mail and slack configuration specified
     * in this config file.
     */
    'notifiable' => \Spatie\FailedJobMonitor\Notifiable::class,

    /**
     * The channels to which the notification will be sent.
     */
    'channels' => ['mail', 'slack'],

    'mail' => [
        'to' => 'email@example.com',
    ],

    'slack' => [
        'webhook_url' => env('FAILED_JOB_SLACK_WEBHOOK_URL'),
    ],
];
``` 

## Configuration

### Customizing the notification
 
The default notification class provided by this package has support for mail and Slack. 

If you want to customize the notification you can specify your own notification class in the config file.

```php
// config/laravel-failed-job-monitor.php
return [
    ...
    'notification' => \App\Notifications\CustomNotificationForFailedJobMonitor::class,
    ...
```

### Customizing the notifiable
 
The default notifiable class provided by this package use the `channels`, `mail` and `slack` keys from the `config` file to determine how notifications must be sent
 
If you want to customize the notifiable you can specify your own notifiable class in the config file.

```php
// config/laravel-failed-job-monitor.php
return [
    'notifiable' => \App\CustomNotifiableForFailedJobMonitor::class,
    ...
```

## Usage

If you configured the package correctly, you're done. You'll receive a notification when a queued job fails.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

All postcards are published [on our website](https://spatie.be/en/opensource/postcards).

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

A big thank you to [Egor Talantsev](https://github.com/spyric) for his help creating `v2` of the package.

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
