<?php

namespace Bagoesz21\LaravelNotification\Notifications\Traits;

use Illuminate\Support\Arr;

trait HasTagAndMetaData
{
    public $tag;

    /** @var array */
    public $metaData = [];

    /**
     * Set tag
     *
     * @param  string  $tag
     * @return self
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Set meta data
     *
     * @param  array  $metaData
     *                           Ex : [
     *                           'comment_id' => 123
     *                           ]
     * @return self
     */
    public function setMetaData($metaData)
    {
        if (empty($metaData)) {
            return $this;
        }
        $this->metaData = Arr::wrap($metaData);

        return $this;
    }
}
