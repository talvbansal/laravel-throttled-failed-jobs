<?php

namespace TalvBansal\ThrottledFailedJobMonitor\Event;

use Illuminate\Foundation\Events\Dispatchable;

class NotificationLimitReached
{
    use Dispatchable;

    /**
     * @var string
     */
    public $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }
}
