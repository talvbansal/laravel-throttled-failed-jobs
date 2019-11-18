<?php

namespace TalvBansal\ThrottledFailedJobMonitor;

class Notifiable
{
    use RoutesThrottledNotifications;

    public function routeNotificationForMail(): string
    {
        return config('throttled-failed-job.mail.to');
    }

    public function routeNotificationForSlack(): string
    {
        return config('throttled-failed-job.slack.webhook_url');
    }

    public function routeNotificationForMsteams()
    {
        return config('throttled-failed-job.ms-teams.webhook_url');
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return 1;
    }
}
