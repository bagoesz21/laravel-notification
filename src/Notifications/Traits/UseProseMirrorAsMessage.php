<?php

namespace Bagoesz21\LaravelNotification\Notifications\Traits;

use App\Services\ProseMirror\Renderer;
use Illuminate\View\View;

trait UseProseMirrorAsMessage
{
    public function afterInitProse()
    {
        $this->setMessageToProseMirror();
    }

    /**
     * Set message notification to prose mirror scheme
     *
     * @param  mixed|null  $message
     * @return mixed
     */
    public function setMessageToProseMirror($message = null)
    {
        if (is_null($message)) {
            $message = $this->getMessage();
        }
        if (is_null($message)) {
            return false;
        }
        $isMessageAsProseMirror = $this->isProseMirrorScheme($message);

        if (! $isMessageAsProseMirror) {
            $proseMirror = $this->convertStringToProseMirror($message);
            $this->setMessage(json_encode($proseMirror));
        }

        return $this->getMessage();
    }

    /**
     * Convert string to prose mirror scheme
     *
     * @param  string  $string
     * @return array
     */
    public function convertStringToProseMirror($string)
    {
        return $this->getRenderer()
            ->setContent('<p>'.$string.'</p>')
            ->getDocument();
    }

    /**
     * Get renderer prosemirror
     *
     * @param  mixed|null  $content
     * @return \App\Services\ProseMirror\Renderer
     */
    public function getRenderer($content = null)
    {
        $renderer = new Renderer([], false, false, true);
        $content = is_null($content) ? $this->getMessage() : $content;

        if (! is_null($content)) {
            $renderer->setContent($content);
        }

        if ($this->enableUTM) {
            $renderer->insertUTM($this->getUTMAsKeyValue());
        }

        return $renderer;
    }

    /**
     * Convert prose mirror to HTML
     *
     * @param  object|string  $scheme
     * @param  bool  $lazyImg
     * @return string
     */
    public function proseMirrorToHTML($scheme, $lazyImg = false)
    {
        if (empty($scheme)) {
            return '';
        }

        $renderer = $this->getRenderer($scheme);
        if (! $lazyImg) {
            $renderer->addImageNotLazy();
        }

        return $renderer->getHTML();
    }

    /**
     * Check document is valid prose mirror scheme or not.
     *
     * @param  mixed  $document
     * @return bool
     */
    public function isProseMirrorScheme($document)
    {
        if (empty($document)) {
            return false;
        }

        $renderer = $this->getRenderer($document);

        return $renderer->isDocumentValid();
    }

    /**
     * ------------------------
     * Ovveride
     * ------------------------
     */

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
        $this->setMessage($this->getRenderer($html)->getJSON());

        return $this;
    }

    /**
     * Get message notif as plain text
     *
     * @return string
     */
    public function getMessageAsPlainText()
    {
        $isMessageAsProseMirror = $this->isProseMirrorScheme($this->getMessage());
        if (! $isMessageAsProseMirror) {
            return $this->getMessage();
        }

        $renderer = $this->getRenderer();

        return $renderer->getText();
    }

    /**
     * Get message notif as HTML
     *
     * @return string
     */
    public function getMessageAsHTML()
    {
        return $this->proseMirrorToHTML($this->getMessage());
    }
}
