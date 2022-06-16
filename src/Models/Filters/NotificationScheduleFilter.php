<?php

namespace Bagoesz21\LaravelNotification\Models\Filters;

use EloquentFilter\ModelFilter;
use Illuminate\Support\Arr;
use Bagoesz21\LaravelNotification\Models\NotificationSchedule;
use Bagoesz21\LaravelNotification\Models\Notification;

class NotificationScheduleFilter extends ModelFilter
{
    use Traits\DefaultFilterDataTrait;

    public $relations = [];

    private function getTableName()
    {
        return NotificationSchedule::getTableName();
    }

    public function targets($targets)
    {
        return $this->whereIn("{$this->getTableName()}.target_id", Arr::wrap($targets));
    }

    public function types($types)
    {
        return $this->whereIn('notification_type', Notification::getFullClassNotificationType($types));
    }

    public function status($status)
    {
        switch (strtolower($status)) {
            case 'sent':
                $this->whereNotNull('sent_at');
                break;
            case 'reschedule':
                $this->whereNotNull('rescheduled_at');
                break;
            case 'cancelled':
                $this->whereNotNull('cancelled_at');
                break;

            default:
                break;
        }
        return $this;
    }
}
