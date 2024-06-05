<?php

namespace Bagoesz21\LaravelNotification\Services\Message;

class MessageParser extends BaseMessageParser
{
    public function isValid(): bool
    {
        return true;
    }

    public function toText(): string
    {
        return $this->message;
    }

    public function toHtml(): string
    {
        return $this->stringToScheme($this->message);
    }

    public function stringToScheme(string $string)
    {
        return '<p>'.$string.'</p>';
    }
}
