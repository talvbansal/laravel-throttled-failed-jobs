<?php

namespace TalvBansal\ThrottledFailedJobMonitor;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification as IlluminateNotification;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Str;
use NotificationChannels\MsTeams\MsTeamsMessage;

class Notification extends IlluminateNotification implements ThrottledNotification
{
    /** @var \Illuminate\Queue\Events\JobFailed */
    protected $event;

    public function via($notifiable): array
    {
        return config('throttled-failed-jobs.channels');
    }

    public function setEvent(JobFailed $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getEvent(): JobFailed
    {
        return $this->event;
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject('A job failed at '.config('app.url'))
            ->line("Exception message: {$this->event->exception->getMessage()}")
            ->line("Job class: {$this->event->job->resolveName()}")
            ->line("Job body: {$this->event->job->getRawBody()}")
            ->line("Exception: {$this->event->exception->getTraceAsString()}");
    }

    public function toSlack(): SlackMessage
    {
        return (new SlackMessage)
            ->error()
            ->content('A job failed at '.config('app.url'))
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->fields([
                    'Exception message' => $this->event->exception->getMessage(),
                    'Job class' => $this->event->job->resolveName(),
                    'Job body' => $this->event->job->getRawBody(),
                    'Exception' => $this->event->exception->getTraceAsString(),
                ]);
            });
    }

    public function toMsTeams(): MsTeamsMessage
    {
        $content = sprintf('## Job class : %s
> Exception message: %s
Job body: %s
    ```php
        %s
    ```
        ',
            $this->event->exception->getMessage(),
            $this->event->job->resolveName(),
            $this->event->job->getRawBody(),
            $this->event->exception->getTraceAsString()
        );

        return MsTeamsMessage::create()
            ->title('A job failed at '.config('app.url'))
            ->content($content);
    }

    public function throttleDecayMinutes(): int
    {
        return config('throttled-failed-jobs.throttle_decay');
    }

    public function throttleKeyId()
    {
        if ($this->getEvent()->exception instanceof \Exception) {
            return Str::kebab($this->getEvent()->exception->getMessage());
        }

        // fall back throttle key, use the notification name...
        return static::class;
    }
}
