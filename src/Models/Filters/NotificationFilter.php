<?php

namespace Bagoesz21\LaravelNotification\Models\Filters;

use EloquentFilter\ModelFilter;
use Illuminate\Support\Arr;
use App\Models\Filters\DefaultFilterDataTrait;
use App\Models\Notification;

class NotificationFilter extends ModelFilter
{
    use DefaultFilterDataTrait;

    public $relations = [];

    private function getTableName(){
        return Notification::getTableName();
    }

    public function users($users)
    {
        return $this->whereIn("{$this->getTableName()}.notifiable_id", Arr::wrap($users));
    }

    public function types($types)
    {
        return $this->whereIn('type', Notification::getFullClassNotificationType($types));
    }

    public function readAt($readAt)
    {
        return $this->whereIn('read_at', $readAt);
    }

    public function isRead($isRead)
    {
        if($isRead){
            $this->whereNotNull('read_at');
        }else {
            $this->whereNull('read_at');
        }
    }

    public function unread($unread)
    {
        return $this->whereNull('read_at');
    }

    public function level($level)
    {
        return $this->whereIn('level', Arr::wrap($level));
    }
}
