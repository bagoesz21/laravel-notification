<?php

namespace Bagoesz21\LaravelNotification\Models\Filters;

use EloquentFilter\ModelFilter;
use Illuminate\Support\Arr;
use Bagoesz21\LaravelNotification\Helpers\NotifHelper;

class NotificationLogFilter extends ModelFilter
{
    use Traits\DefaultFilterDataTrait;

    public $relations = [];

    /** @var \Bagoesz21\LaravelNotification\Models\NotificationLog */
    public $model;

    public function __construct($query, array $input = [], $relationsEnabled = true)
    {
        parent::__construct($query, $input, $relationsEnabled);
        $this->model = NotifHelper::getNotifLogModelClass();
    }

    private function getTableName(){
        return $this->model::getTableName();
    }
}
