<?php

namespace Bagoesz21\LaravelNotification\Notifications\Actions;

class BaseActionNotification
{
    /** @var string */
    protected $type;
    /** @var array */
    protected $requires = []; //always save attribute

    protected $url;
    protected $title;
    protected $icon;
    protected $tooltip;
    protected $color;
    protected $class;

    /**
     * Static create action notification
     *
     * @param string $url
     * @param string|null $title
     * @return static
     */
    public static function create($url, $title = null){
        $class = get_called_class();
        return (new $class())->url($url)->title($title);
    }

    /**
     * @param string $title
     * @return self
     */
    public function title($title){
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $url
     * @return self
     */
    public function url($url){
        // $this->url = str_replace(config('app.url'), '', $url);
        $this->url = $url;
        return $this;
    }

    /**
     * @param string $icon
     * @return self
     */
    public function icon($icon){
        $this->icon = $icon;
        return $this;
    }

    /**
     * @param string $string
     * @return self
     */
    public function tooltip($string){
        $this->tooltip = $string;
        return $this;
    }

    /**
     * @param string $class
     * @return self
     */
    public function setClass($class){
        $this->class = $class;
        return $this;
    }

    /**
     * @param string $color
     * @return self
     */
    public function color($color){
        $this->color = $color;
        return $this;
    }

    /**
     * Merge all attribute action into array
     *
     * @return array
     */
    public function toArray(){
        $vars = get_class_vars(get_class($this));

        $vars = array_filter($vars, function($var){
            return !in_array($var, ['requires']);
        }, ARRAY_FILTER_USE_KEY);

        $result = [];
        foreach ($vars as $key => $var) {
            if(empty($this->{$key}) && (!in_array($key, $this->requires))) continue;
            $result[$key] = $this->{$key};
        }
        return $result;
    }
}
