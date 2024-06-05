<?php

namespace Bagoesz21\LaravelNotification\Services\Message;

abstract class BaseMessageParser implements MessageParserInterface
{
    protected $message;

    public function __construct($message = null)
    {
        $this->setMessage($message);
    }

    /**
     * @return static
     */
    public static function make()
    {
        $class = get_called_class();

        return new $class();
    }

    public function setMessage($message = null)
    {
        $this->message = $message;

        return $this;
    }
}
