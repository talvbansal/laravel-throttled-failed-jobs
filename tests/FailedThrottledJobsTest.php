<?php

namespace TalvBansal\ThrottledFailedJobMonitor;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use TalvBansal\ThrottledFailedJobMonitor\Dummy\AnotherNotifiable;
use TalvBansal\ThrottledFailedJobMonitor\Dummy\AnotherNotification;
use TalvBansal\ThrottledFailedJobMonitor\Dummy\Job;
use TalvBansal\ThrottledFailedJobMonitor\Event\NotificationLimitReached;

class FailedThrottledJobsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        NotificationFacade::fake();

        Event::fake([
            NotificationLimitReached::class,
        ]);

        $this->artisan('cache:clear');
    }

    /** @test */
    public function it_can_send_notification_when_a_job_failed()
    {
        $this->fireFailedEvent();

        NotificationFacade::assertSentTo(new Notifiable(), Notification::class);
    }

    /** @test */
    public function it_can_throttle_notifications_on_failure()
    {
        $this->fireFailedEvent();

        NotificationFacade::assertSentTo(new Notifiable(), Notification::class);

        $this->fireFailedEvent();

        Event::assertDispatched(NotificationLimitReached::class, 1);
    }

    /** @test */
    public function it_can_send_notification_when_job_failed_to_different_notifiable()
    {
        $this->app['config']->set('throttled-failed-jobs.notifiable', AnotherNotifiable::class);

        $this->fireFailedEvent();

        NotificationFacade::assertSentTo(new AnotherNotifiable(), Notification::class);
    }

    /** @test */
    public function it_can_send_notification_when_job_failed_to_different_notification()
    {
        $this->app['config']->set('throttled-failed-jobs.notification', AnotherNotification::class);

        $this->fireFailedEvent();

        NotificationFacade::assertSentTo(new Notifiable(), AnotherNotification::class);
    }

    /** @test */
    public function it_filters_out_notifications_when_the_notificationFilter_returns_false()
    {
        $this->app['config']->set('throttled-failed-jobs.callback', [$this, 'returnsFalseWhenExceptionIsEmpty']);

        $this->fireFailedEvent();

        NotificationFacade::assertNotSentTo(new Notifiable(), AnotherNotification::class);
    }

    protected function fireFailedEvent(string $message = 'Job Failed')
    {
        return event(new JobFailed('test', new Job(), new \Exception($message)));
    }

    public function returnsFalseWhenExceptionIsEmpty($notification)
    {
        $message = $notification->getEvent()->exception->getMessage();

        return ! empty($message);
    }
}
