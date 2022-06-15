<?php

namespace Bagoesz21\LaravelNotification\Models\Filters\Traits;

use Carbon\Carbon;
use Illuminate\Support\Arr;

trait DefaultFilterDataTrait
{
    /** Get model class name */
    public function modelClass()
    {
        return get_class($this->query->getModel());
    }

    /** Get model table name */
    public function getTable()
    {
        return $this->query->getModel()->getTable();
    }

    /**
     * Filter by id primary key
     *
     * @param integer|string|array $id
     * @return self
     */
    public function id($id)
    {
        return $this->whereIn("{$this->getTable()}.id", Arr::wrap($id));
    }

    /**
     * Filter soft delete data. Require soft delete feature
     *
     * @param string $softDelete
     * @return void
     */
    public function softDelete($softDelete)
    {
        switch (strtolower($softDelete)) {
            //get only deleted data
            case 'only_trashed':
                $this->onlyTrashed();
                break;

            //get data + deleted data
            case 'with_trashed':
                $this->withTrashed();
                break;

            default:
                break;
        }
    }

    public function formatDateToString($date)
    {
        if ($date instanceof Carbon) {
            return $date->format('Y-m-d H:i:s');
        }
        return $date;
    }
}
