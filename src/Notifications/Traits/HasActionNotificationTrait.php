<?php

namespace Bagoesz21\LaravelNotification\Notifications\Traits;

use Illuminate\Support\Arr;
use Bagoesz21\LaravelNotification\Notifications\Actions\BaseActionNotification;

trait HasActionNotificationTrait
{
    /**
     * Set action notification
     *
     * @param array|\Bagoesz21\LaravelNotification\Notifications\Actions\BaseActionNotification $actions
     * @return self
     */
    public function setActions($actions)
    {
        $actions = Arr::wrap($actions);
        $actions = array_filter($actions, function($action){
            return (($action instanceof BaseActionNotification));
        });

        if(empty($actions))return $this;

        $actions = array_map(function($action){
            return $action->toArray();
        }, $actions);

        $this->data = array_merge($this->data, [
            'actions' => $actions
        ]);
        return $this;
    }

    /**
     * Get data action notification
     *
     * @return array
     */
    public function getActions()
    {
        return $this->getData('actions');
    }
}
