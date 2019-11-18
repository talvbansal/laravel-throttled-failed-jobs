<?php

namespace TalvBansal\ThrottledFailedJobMonitor;

use Illuminate\Cache\RateLimiter;
use Illuminate\Notifications\RoutesNotifications;
use Illuminate\Support\Facades\Log;

trait RoutesThrottledNotifications
{
    use RoutesNotifications {
        RoutesNotifications::notify as parentNotify;
    }

    public function notify($instance) : void
    {
        if ($instance instanceof ThrottledNotification) {
            $key = $this->throttleKey($instance);
            if ($this->limiter()->tooManyAttempts($key, $this->maxAttempts())) {
                Log::notice("Skipping sending notification with key `$key`. Rate limit reached.");

                return;
            }

            $this->limiter()->hit($key, $instance->throttleDecayMinutes());
        }

        // Execute the original notify() method.
        $this->parentNotify($instance);
    }

    /**
     * Get the rate limiter instance.
     */
    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    /**
     * Build the notification throttle key from the Notification class name,
     * the Notification's throttle key id and the current users id.
     * @param ThrottledNotification $instance
     * @return string
     */
    protected function throttleKey(ThrottledNotification $instance)
    {
        return mb_strtolower(
            class_basename($instance).'-'.$instance->throttleKeyId()
        );
    }

    /**
     * Set the max attempts to 1.
     */
    protected function maxAttempts()
    {
        return 1;
    }
}
