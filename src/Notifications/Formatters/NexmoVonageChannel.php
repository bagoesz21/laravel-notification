<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use Illuminate\Support\Arr;
use Illuminate\Notifications\Messages\NexmoMessage;

/**
 * Notification to nexmo / vonage
 *
 * @see https://laravel.com/docs/8.x/notifications#sms-notifications
 */
trait NexmoVonageChannel
{
    protected bool $unicode = false;

    protected string $fromNexmo;

    /**
     * Set unicode
     *
     * @param bool $toggle
     * @return self
     */
    public function unicode($toggle = true)
    {
        $this->unicode = $toggle;
        return $this;
    }

    /**
     * Set from Nexmo phone number
     *
     * @param string $from
     * @return self
     */
    public function setFromNexmo($from)
    {
        $this->fromNexmo = $from;
        return $this;
    }

    /**
     * Get the Vonage / SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        $channel = (new NexmoMessage)
            ->clientReference((string) $notifiable->id)
            ->content($this->getMessageAsPlainText());

        if($this->unicode){
            $channel = $channel->unicode();
        }

        if(!empty($this->fromNexmo)){
            $channel = $channel->from($this->fromNexmo);
        }
        return $channel;
    }
}
