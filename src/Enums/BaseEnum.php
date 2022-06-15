<?php

namespace Bagoesz21\LaravelNotification\Enums;

use BenSampo\Enum\Enum;

abstract class BaseEnum extends Enum
{
    use Traits\EnumHasColor;
    use Traits\EnumHasIcon;

    public function __construct($value, ...$args)
    {
        $value = $this->handleEnumValue($value);
        parent::__construct($value, ...$args);

        $this->color = static::getColor($value);
        $this->icon = static::getIcon($value);
    }

    /**
     * Handle initiate enum value.
     * If value not in list enum, set default enum
     *
     * @param mixed
     * @return mixed
     */
    private function handleEnumValue($value){
        if (!in_array($value, array_values(static::getConstants()))) {
            $value = static::getDefaultValue();
        } elseif (is_string($value) && is_numeric($value)) {
            $value = 0 + $value;
        }
        return $value;
    }

    /**
    * Get default enum value
    *
    * @return mixed
    * @throws \Exception
    */
    public static function getDefaultValue()
    {
        throw new \Exception('EnumDefault: Default value is not defined');
    }

    /**
    * Transform the enum instance when it's converted to an array.
    *
    * @return string|array
    */
    public function toArray()
    {
        return $this;
    }

    /**
     * Convert all enum to array.
     *
     * @return array
    */
    public static function getAllAsArray()
    {
        $return = [];
        foreach (self::getInstances() as $enum) {
            $return[] = (array)$enum;
        }
        return $return;
    }
}
