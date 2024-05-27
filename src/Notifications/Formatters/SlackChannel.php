<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Support\Arr;

/**
 * Notification to broadcast
 *
 * @see https://laravel.com/docs/notifications#slack-notifications
 */
trait SlackChannel
{
    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $channel = (new SlackMessage)
            ->content($this->getMessageAsPlainText());

        $attachments = $this->getData('attachments');

        if ($this->isSuccessNotif()) {
            $channel = $channel->success();
        }

        if ($this->isErrorNotif()) {
            $channel = $channel->error();
        }

        if (! empty($attachments)) {
            foreach ($attachments as $key => $attachment) {

                $channel = $channel->attachment(function ($attachmentChannel) use ($attachment) {
                    $type = Arr::get($attachment, 'type', 'file');
                    $filename = Arr::get($attachment, 'filename');
                    $attachmentChannel->title($filename)
                        ->content($filename);

                    $attachmentChannel->fields([
                    ]);
                });
            }
        }

    }
}
