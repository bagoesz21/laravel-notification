<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use Illuminate\Support\Arr;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Notification to mail
 *
 * @see https://laravel.com/docs/8.x/notifications#mail-notifications
 */
trait MailChannel
{
    protected $mailer;

    protected $theme;

    protected $tag;

    /** @var array */
    protected $metaData = [];

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

    /**
     * Set tag
     *
     * @param string $tag
     * @return self
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * Set meta data
     *
     * @param array $metaData
     * Ex : [
     *  'comment_id' => 123
     * ]
     * @return self
     */
    public function setMetaData($metaData)
    {
        if(empty($metaData))return $this;
        $this->metaData = Arr::wrap($metaData);
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
                $mail = $mail->attach($attachment['url'], $attachment['options']);
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
