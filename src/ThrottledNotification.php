<?php

namespace TalvBansal\ThrottledFailedJobMonitor;

interface ThrottledNotification
{
    public function throttleDecayMinutes(): int;

    public function throttleKeyId();
}
