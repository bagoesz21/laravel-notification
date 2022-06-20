<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use Illuminate\Support\Arr;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notification to mail
 *
 * @see https://laravel.com/docs/notifications#mail-notifications
 */
trait MailChannel
{
    protected $mailer;

    protected $theme;

    /**
     * Init mail
     *
     * @return self
     */
    public function initMail()
    {
        $this->setSelectedChannel('mail');

        $mailer = Arr::get($this->getChannel(), 'mailer', env('MAIL_MAILER', 'smtp'));
        $this->setMailer($mailer)->enableUTM(true);
        return $this;
    }

    /**
     * Set mailer
     *
     * @param string $mailer
     * @return self
     */
    public function setMailer($mailer)
    {
        $this->mailer = $mailer;
        return $this;
    }

    /**
     * Set theme
     *
     * @param string $theme
     * @return self
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    public function toMail($notifiable)
    {
        $subject = $this->getTitle();

        $messageHTML = $this->getMessageAsHTML();

        $attachments = $this->getData('attachments');

        $mail = (new MailMessage)->mailer($this->mailer);
        if(!empty($attachments)){
            foreach ($attachments as $key => $attachment) {
                $options = Arr::get($attachment, 'options', []);
                $fromStorage = Arr::get($options, 'fromStorage', false);
                $fileName = Arr::get($attachment, 'filename');
                $fileMime = Arr::get($options, 'mime');

                if($fromStorage){
                    $mail = $mail->attachFromStorage($attachment['path'], $fileName, array_merge([], [
                        'mime' => $fileMime
                    ]));
                }else{
                    $mail = $mail->attach($attachment['url'], $options);
                }
            }
        }

        if($this->isErrorNotif()){
            $mail = $mail->error();
        }

        if(!empty($this->theme)){
            $mail = $mail->theme($this->theme);
        }

        if(!empty($this->tag)){
            $mail = $mail->tag($this->tag);
        }

        if(!empty($this->metaData)){
            foreach ($this->metaData as $key => $meta) {
                $mail = $mail->metadata($key, $meta);
            }
        }

        $utmQuery = $this->getUTMAsQuery();
        $uriQuery = $utmQuery;

        $mail = $mail->markdown('emails.default-notif-mail', [
            'subject' => $subject,
            'title' => $subject,
            'message_html' => $messageHTML,
            'data' => $this->data,
            'utm_query' => $utmQuery,
            'uri_query' => $uriQuery
        ])
        ->subject($subject);
        return $mail;
    }
}
