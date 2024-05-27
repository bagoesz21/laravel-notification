<?php

namespace Bagoesz21\LaravelNotification\Models\Filters;

use Bagoesz21\LaravelNotification\Helpers\NotifHelper;
use EloquentFilter\ModelFilter;

class NotificationTemplateFilter extends ModelFilter
{
    use Traits\DefaultFilterDataTrait;

    public $relations = [];

    /** @var \Bagoesz21\LaravelNotification\Models\NotificationTemplate */
    public $model;

    public function __construct($query, array $input = [], $relationsEnabled = true)
    {
        parent::__construct($query, $input, $relationsEnabled);
        $this->model = NotifHelper::getNotifTemplateModelClass();
    }

    private function getTableName()
    {
        return $this->model::getTableName();
    }
}
