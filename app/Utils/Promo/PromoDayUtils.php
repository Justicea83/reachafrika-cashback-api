<?php

namespace App\Utils\Promo;

use Illuminate\Support\Str;

class PromoDayUtils
{
    const ONCE = 'Once';
    const ALL_DAYS = 'All days';
    const MONDAYS_TO_FRIDAYS = 'Mondays - Fridays';
    const SATURDAYS_TO_SUNDAYS = 'Saturdays - Sundays';
    const MONDAYS = 'Mondays';
    const TUESDAYS = 'Tuesdays';
    const WEDNESDAYS = 'Wednesdays';
    const THURSDAYS = 'Thursdays';
    const FRIDAYS = 'Fridays';
    const SATURDAYS = 'Saturdays';
    const SUNDAYS = 'Sundays';

    public static function slugify(string $day): string
    {
        return trim(Str::slug(str_replace(' ', '', $day)));
    }
}
