<?php

namespace Bagoesz21\LaravelNotification\Models\Collections;

use Illuminate\Notifications\DatabaseNotificationCollection as Collection;

class NotificationCollection extends Collection
{
    /*
    * Custom Collection User Notification for readable notification to display user
    */
    public function toReadable()
    {
        return $this->transform(function ($item) {
            $item['type_text'] = $item->getAttributeValue('type_text');
            $item['data_formatted'] = $item->getAttributeValue('formatted_data');
            $item['is_read'] = $item->getAttributeValue('is_read');

            return $item;
        });
    }
}
