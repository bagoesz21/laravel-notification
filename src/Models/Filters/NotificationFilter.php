<?php

namespace Bagoesz21\LaravelNotification\Models\Filters;

use Bagoesz21\LaravelNotification\Helpers\NotifHelper;
use EloquentFilter\ModelFilter;
use Illuminate\Support\Arr;

class NotificationFilter extends ModelFilter
{
    use Traits\DefaultFilterDataTrait;

    public $relations = [];

    /** @var \Bagoesz21\LaravelNotification\Models\Notification */
    public $model;

    public function __construct($query, array $input = [], $relationsEnabled = true)
    {
        parent::__construct($query, $input, $relationsEnabled);
        $this->model = NotifHelper::getNotifModelClass();
    }

    private function getTableName()
    {
        return $this->model::getTableName();
    }

    public function users($users)
    {
        return $this->whereIn("{$this->getTableName()}.notifiable_id", Arr::wrap($users));
    }

    public function types($types)
    {
        return $this->whereIn('type', $this->model::getFullClassNotificationType($types));
    }

    public function readAt($readAt)
    {
        return $this->whereIn('read_at', $readAt);
    }

    public function isRead($isRead)
    {
        if ($isRead) {
            $this->whereNotNull('read_at');
        } else {
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
