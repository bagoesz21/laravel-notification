<?php

namespace Bagoesz21\LaravelNotification\Helpers;

class Helper
{
    /**
     * This function for create validation single array based form request
     * Example: appendArrayKey(with(new UserDetailRequest)->rules(), 'detail')
     * Output Result :
     * "detail.birth_city_id" => "required"
     * "detail.birth_city_name" => "required"
     *
     * @param  array  $array  array data
     * @param  string  $appendText  array name
     * @param  string|null  $separatorNotation  seperator notation. Default dot notation (.)
     * @return array $result
     **/
    public static function appendArrayKey($array, $appendText, $separatorNotation = '.')
    {
        if (empty($array)) {
            return [];
        }
        $result = [];

        $appendText = $appendText.$separatorNotation;

        foreach ($array as $key => $value) {
            $result[$appendText.$key] = $value;
        }

        return $result;
    }

    /**
     * This function for create validation multiple array based form request
     * Example: appendArrayKeyWildcard(with(new EventScheduleRequest)->rules(), 'schedules', "*", ".")
     * Output Result :
     * "schedules.*.date_start" => "required|date"
     * "schedules.*.date_end" => "required|date"
     *
     * @param  array  $array  array data
     * @param  string  $appendText  array name
     * @param  string|null  $wildcard.  Default: (*)
     * @param  string|null  $separatorNotation  seperator notation. Default: dot notation (.)
     * @return array $result
     **/
    public static function appendArrayKeyWildcard($array, $appendText, $wildcard = '*', $separatorNotation = '.')
    {
        if (empty($array)) {
            return [];
        }
        $result = [];

        $appendText = $appendText.$separatorNotation.$wildcard.$separatorNotation;

        foreach ($array as $key => $value) {
            $result[$appendText.$key] = $value;
        }

        return $result;
    }
}
