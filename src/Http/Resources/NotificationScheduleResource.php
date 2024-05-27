<?php

namespace Bagoesz21\LaravelNotification\Http\Resources;

use App\Services\ProseMirror\Renderer;

class NotificationScheduleResource extends BaseJsonResource
{
    public function custom()
    {
        $unserializeNotif = $this->unserialize_notification;

        $notifData = [];
        if ($unserializeNotif) {
            $renderer = new Renderer();
            $renderer->setContent($unserializeNotif->message);
            $message_html = $renderer->getHTML();

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
