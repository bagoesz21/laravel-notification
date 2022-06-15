<?php

namespace Bagoesz21\LaravelNotification\Http\Resources;

class NotificationResource extends BaseJsonResource
{
    public function custom()
    {
        return [
            'type_text' => $this->type_text,
            'message_html' => $this->message_html,

            'image_url' => $this->image_url,

            //'data' => $this->data,
            'data' => $this->formatted_data,
        ];
    }
}
