<?php


namespace TalvBansal\ThrottledFailedJobMonitor;



class Notifiable
{
    use RoutesThrottledNotifications;

    public function routeNotificationForMail(): string
    {
        return config('failed-job-monitor.mail.to');
    }

    public function routeNotificationForSlack(): string
    {
        return config('failed-job-monitor.slack.webhook_url');
    }

    public function routeNotificationForMsTeams()
    {
        return config('failed-job-monitor.ms-teams.webhook_url');
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return 1;
    }
}
