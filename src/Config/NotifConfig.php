<?php

namespace Bagoesz21\LaravelNotification\Config;

use Bagoesz21\LaravelNotification\Config\Mapper\BaseNotifMapper;
use Illuminate\Support\Arr;

class NotifConfig
{
    /** @var \Bagoesz21\LaravelNotification\Config\Mapper\BaseNotifMapper */
    protected $mapper;

    protected $config = [];

    /**
     * @param  mixed|null  $mapper
     * @return self
     */
    public static function make($mapper = null)
    {
        return new self($mapper);
    }

    /**
     * @param  mixed|null  $mapper
     */
    public function __construct($mapper = null)
    {
        $this->setMapper(! is_null($mapper) ? $mapper : config('notification.mapper'));
    }

    public function setMapper($mapper)
    {
        $this->mapper = app($mapper);

        return $this;
    }

    public function toArray()
    {
        if (! ($this->mapper instanceof BaseNotifMapper)) {
            return null;
        }

        return $this->mapper->toArray();
    }

    public function get($key, $default = null)
    {
        return Arr::get($this->toArray(), $key, $default);
    }

    public function getLocale()
    {

    }
}
