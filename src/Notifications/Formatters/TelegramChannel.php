<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use Illuminate\Support\Arr;
use NotificationChannels\Telegram\TelegramMessage;

/**
 * Notification to telegram
 *
 * @see https://laravel-notification-channels.com/telegram
 * @see https://core.telegram.org/bots
 */
trait TelegramChannel
{
    public function toTelegram($notifiable)
    {
        $channel = TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->content($this->getMessageAsPlainText());

        $attachments = $this->getData('attachments');

        if (! empty($attachments)) {
            foreach ($attachments as $key => $attachment) {
                $type = Arr::get($attachment, 'type', 'file');
                $filename = Arr::get($attachment, 'filename');
                switch ($type) {
                    case 'photo':
                        $channel = $channel->photo($attachment['url'], $attachment['type']);
                        break;
                    case 'audio':
                        $channel = $channel->voice($attachment['url']);
                        break;
                    case 'video':
                        $channel = $channel->video($attachment['url']);
                        break;
                    case 'animation':
                        $channel = $channel->animation($attachment['url']);
                        break;
                    case 'document':
                        $channel = $channel->document($attachment['url'], $filename);
                        break;

                    default: //file
                        $channel = $channel->file($attachment['url'], $type, $filename);
                        break;
                }
            }
        }

        $actions = $this->getActions();
        if (! empty($actions)) {
            foreach ($actions as $key => $action) {
                $channel = $channel->button(Arr::get($action, 'title'), Arr::get($action, 'url'));
            }
        }

        return $channel;
    }
}
