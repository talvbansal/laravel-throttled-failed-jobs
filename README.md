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
$ composer test
```

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Talv Bansal](https://github.com/talvbansal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

Please see [this repo](https://github.com/laravel-notification-channels/channels) for instructions on how to submit a channel proposal.
