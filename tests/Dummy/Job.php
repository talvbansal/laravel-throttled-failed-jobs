<?php

namespace TalvBansal\ThrottledFailedJobMonitor\Dummy;

class Job
{
    public function __construct()
    {
        \Log::debug('Job created');
    }
}
