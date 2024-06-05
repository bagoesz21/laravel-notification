<?php

namespace Bagoesz21\LaravelNotification\Services\Message;

interface MessageParserInterface
{
    public function isValid(): bool;

    public function toText(): string;

    public function toHtml(): string;
}
