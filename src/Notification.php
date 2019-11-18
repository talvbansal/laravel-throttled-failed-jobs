<?php


namespace TalvBansal\ThrottledFailedJobMonitor;


use Illuminate\Queue\Events\JobFailed;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use NotificationChannels\MsTeams\MsTeamsChannel;
use NotificationChannels\MsTeams\MsTeamsMessage;
use Illuminate\Notifications\Notification as IlluminateNotification;

class Notification extends IlluminateNotification implements ThrottledNotification
{
    /** @var \Illuminate\Queue\Events\JobFailed */
    protected $event;

    public function via($notifiable): array
    {
        return config('failed-job-monitor.channels');
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

    public function toMsTeams(): MsTeamsMessage{

        $content = sprintf("## Job class : %s
        > Exception message: %s
        Job body: %s
        ```php
            %s
        ```
        ",
            $this->event->exception->getMessage(),
            $this->event->job->resolveName(),
            $this->event->exception->getRawBody(),
            $this->event->exception->getTraceAsString(),
        );

        return MsTeamsMessage::create()
            ->title($this->event->exception->getMessage())
            ->content($content);
    }

    public function throttleDecayMinutes(): int
    {
        return 1;
    }

    public function throttleKeyId()
    {
        return $this->event->exception->getMessage();
    }
}