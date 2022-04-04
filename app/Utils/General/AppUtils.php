<?php

namespace App\Utils\General;

class AppUtils
{
    const APP_PLATFORM = 'api';

    public static function removeSpacesSpecialChar($str)
    {
        return preg_replace('/[0-9\@\.\;\" "]+/', '', $str);
    }
}
