<?php

namespace Bagoesz21\LaravelNotification\Notifications\Traits;

use Bagoesz21\LaravelNotification\Helpers\NotifHelper;
use Illuminate\View\View;

trait UseMessageParser
{
    public function afterInitProse()
    {
        $this->setMessageToProseMirror();
    }

    public function messageParser($message = null)
    {
        return NotifHelper::messageParser()->setMessage($message);
    }

    public function messageParserToText($message = null)
    {
        return $this->messageParser($message)->toText();
    }

    public function messageParserToHtml($message = null)
    {
        return $this->messageParser($message)->toHtml();
    }

    /**
     * Set message notification to message parser scheme
     *
     * @param  mixed|null  $message
     * @return mixed
     */
    public function setMessageToParse($message = null)
    {
        if (is_null($message)) {
            $message = $this->getMessage();
        }
        if (is_null($message)) {
            return false;
        }

        $isValid = $this->messageParser($message)->isValid();

        if (! $isValid) {
            $result = $this->messageParser()->stringToScheme($message);
            $this->setMessage($result);
        }

        return $this->getMessage();
    }

    /**
     * Set message from view blade
     *
     * @param  \Illuminate\View\View  $view
     * @return self
     */
    public function setMessageFromView($view)
    {
        $html = '';
        if ($view instanceof View) {
            $html = $view->render();
        }

        if (empty($html)) {
            return $this;
        }

        $this->setMessage($html);

        return $this;
    }

    /**
     * Get message notif as plain text
     *
     * @return string
     */
    public function getMessageAsPlainText()
    {
        $isValid = $this->messageParser($this->getMessage())->isValid();

        if (! $isValid) {
            return $this->getMessage();
        }

        return $this->messageParserToText($this->getMessage());
    }

    /**
     * Get message notif as HTML
     *
     * @return string
     */
    public function getMessageAsHTML()
    {
        return $this->messageParserToHtml($this->getMessage());
    }
}
