<?php

namespace TalvBansal\ThrottledFailedJobMonitor;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\QueueManager;
use TalvBansal\ThrottledFailedJobMonitor\Exceptions\InvalidConfiguration;

class FailedJobNotifier
{
    public function register()
    {
        app(QueueManager::class)->failing(function (JobFailed $event) {
            $notifiable = app(config('throttled-failed-job.notifiable'));

            $notification = app(config('throttled-failed-job.notification'))->setEvent($event);

            if (! $this->isValidNotificationClass($notification)) {
                throw InvalidConfiguration::notificationClassInvalid(get_class($notification));
            }

            if ($this->shouldSendNotification($notification)) {
                $notifiable->notify($notification);
            }
        });
    }

    public function isValidNotificationClass($notification): bool
    {
        if (get_class($notification) === Notification::class) {
            return true;
        }

        if (is_subclass_of($notification, Notification::class)) {
            return true;
        }

        return false;
    }

    public function shouldSendNotification($notification)
    {
        $callable = config('throttled-failed-job.notificationFilter');

        if (! is_callable($callable)) {
            return true;
        }

        return $callable($notification);
    }
}
