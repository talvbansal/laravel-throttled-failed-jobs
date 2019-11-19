<?php

namespace TalvBansal\ThrottledFailedJobMonitor\Dummy;

use TalvBansal\ThrottledFailedJobMonitor\RoutesThrottledNotifications;

class AnotherNotifiable
{
    use RoutesThrottledNotifications;

    public function routeNotificationForMail()
    {
        return 'john@example.com';
    }

    public function routeNotificationForSlack()
    {
        return '';
    }

    public function getKey()
    {
        return 1;
    }
}
