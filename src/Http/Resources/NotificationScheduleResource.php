<?php

namespace Bagoesz21\LaravelNotification\Http\Resources;

use Bagoesz21\LaravelNotification\Helpers\NotifHelper;

class NotificationScheduleResource extends BaseJsonResource
{
    public function custom()
    {
        $unserializeNotif = $this->unserialize_notification;

        $notifData = [];
        if ($unserializeNotif) {
            $message_html = NotifHelper::messageParserToHtml($unserializeNotif->message);

            $notifData = [
                'id' => null,
                'title' => $unserializeNotif->title,
                'message' => $unserializeNotif->message,
                'message_html' => $message_html,
                'action_url' => null,
            ];
        }

        return [
            'notifiable_id' => $this->target_id,

            'type_text' => $this->notification_type,
            'data' => $notifData,
        ];
    }
}
