<?php

return [

    /*
     * The notification that will be sent when a job fails.
     */
    'notification' => \TalvBansal\ThrottledFailedJobMonitor\Notification::class,

    /*
     * The notifiable to which the notification will be sent. The default
     * notifiable will use the mail and slack configuration specified
     * in this config file.
     */
    'notifiable' => \TalvBansal\ThrottledFailedJobMonitor\Notifiable::class,

    /*
     * By default notifications are sent for all failures. You can pass a callable to filter
     * out certain notifications. The given callable will receive the notification. If the callable
     * return false, the notification will not be sent.
     */
    'notificationFilter' => null,

    /*
     * The channels to which the notification will be sent.
     */
    'channels' => ['mail', 'slack', 'msteams'],

    'mail' => [
        'to' => 'email@example.com',
    ],

    'slack' => [
        'webhook_url' => env('FAILED_JOB_SLACK_WEBHOOK_URL'),
    ],

    'ms-teams' => [
        'webhook_url' => env('MS_TEAMS_WEBHOOK_URL'),
    ],

    /*
     * The length of the throttle window in minutes. Eg: 10 would mean
     * only one notification of certain type would be actually sent
     * within a 10 minute window...
     */
    'throttle_decay' => 10,
];
