<?php
namespace Bagoesz21\LaravelNotification\Config\Mapper;

abstract class BaseNotifMapper implements NotifMapperInterface
{
    public function __construct()
    {
    }

    /**
     * @return static
     */
    public static function make(){
        $class = get_called_class();
        return (new $class());
    }
}
