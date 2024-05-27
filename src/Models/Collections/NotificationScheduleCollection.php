<?php

namespace Bagoesz21\LaravelNotification\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class NotificationScheduleCollection extends Collection
{
    /*
    * Custom Collection Notification for readable notification to display user
    */
    public function toReadable()
    {
        return $this->transform(function ($item) {
            $item['target_type_text'] = $item->getAttributeValue('target_type_text');
            $item['notification_type_text'] = $item->getAttributeValue('notification_type_text');

            return $item;
        });
    }
}
