<?php

namespace Bagoesz21\LaravelNotification\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DefaultNotifMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data = [];

    public $subject;

    /**
     * Create a new message instance.
     *
     * @param  string|null  $subject
     * @return void
     */
    public function __construct(array $data = [], $subject = null)
    {
        $this->setData($data);
        $this->subject = $subject ?? __('laravel-notification::notification.locale');
    }

    /**
     * @param  array  $data
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('laravel-notification::emails.default-notif-mail', array_merge([], $this->data))
            ->subject($this->subject);
    }
}
