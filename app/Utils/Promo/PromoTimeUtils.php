<?php

namespace App\Utils\Promo;

use Illuminate\Support\Carbon;
use InvalidArgumentException;

class PromoTimeUtils
{
    function s()
    {
        Carbon::now();
    }

    const TIMES = [
        [
            'name_12' => '12:00',
            'name_24' => '00:00'
        ],
        [
            'name_12' => '13:00',
            'name_24' => '00:00'
        ],
        [
            'name_12' => '12:00',
            'name_24' => '00:00'
        ],
        [
            'name_12' => '12:00',
            'name_24' => '00:00'
        ],
        [
            'name_12' => '12:00',
            'name_24' => '00:00'
        ],
        [
            'name_12' => '12:00',
            'name_24' => '00:00'
        ],
        [
            'name_12' => '12:00',
            'name_24' => '00:00'
        ],
        [
            'name_12' => '12:00',
            'name_24' => '00:00'
        ],
    ];

    public static function get24thHourName(int $index): string
    {
        if ($index < 10) return '0' . $index;
        return (string)$index;
    }

    public static function get12thHourName(int $index): string
    {
        if ($index == 0) return "12";
        if ($index < 13) return (string)$index;
        return (string)($index - 12);
    }

    public static function getModulation(int $index): string
    {
        if ($index == 0) return " am";
        if ($index < 12) return ' am';
        if ($index == 12) return " pm";
        return ' pm';
    }

    public static function getMinuteInterval(int $hour,int $index, bool $check = false): string
    {
        $modulation = null;

        if($check){
            $modulation = self::getModulation($hour);
        }

        switch ($index) {
            case 0:
                return $modulation != null ? "00" . $modulation: "00";
            case 1:
                return $modulation != null ? "15" . $modulation: "15";
            case 2:
                return $modulation != null ? "30" . $modulation: "30";
            case 3:
                return $modulation != null ? "45" . $modulation: "45";
            default:
                throw new InvalidArgumentException("argument out of range");
        }
    }
}
