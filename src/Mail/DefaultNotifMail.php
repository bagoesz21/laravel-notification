<?php

namespace Bagoesz21\LaravelNotification\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DefaultNotifMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Array $data;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @param array $data
     * @param string $subject
     * @return void
     */
    public function __construct(Array $data, $subject = "")
    {
        $this->data = $data;
        $this->subject = $subject ?? __('laravel-notification::notification.locale');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('laravel-notification::views.emails.default-notif-mail', [
            'data' => $this->data,
            'subject' => $this->subject
        ])
        ->subject($this->subject);
    }
}
