<?php

namespace Bagoesz21\LaravelNotification\Notifications\Actions;

use Carbon\Carbon;

class ButtonAction extends BaseActionNotification
{
    protected $requires = ['url', 'title'];
    protected $type = 'button';

    public $target;
    /** @var bool */
    public $is_inertia = false;
    /** @var string */
    public $download_filename;
    /** @var string */
    public $expiry_at;
    /** @var string */
    public $tooltip;

    /**
     * @param string $name
     * @return self
     */
    public function downloadFileName($name){
        $this->download_filename = $name;
        return $this;
    }

    /**
     * @param boolean $toggle
     * @return self
     */
    public function isInertia(bool $toggle = true){
        $this->is_inertia = $toggle;
        return $this;
    }

    /**
     * @param \Carbon\Carbon $expiryAt
     * @return self
     */
    public function expireAt(Carbon $expiryAt){
        $this->expiry_at = $expiryAt;
        return $this;
    }

    /**
     * @param string $target
     * @param self
     */
    public function setTarget($target){
        $this->target = $target;
        return $this;
    }

    /**
     * @return self
     */
    public function openNewTab(){
        $this->setTarget('_blank');
        return $this;
    }
}
