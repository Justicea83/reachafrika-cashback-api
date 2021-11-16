<?php

namespace App\Traits;

trait UnixTimestampsFormat
{
    //protected string $dateFormat = 'U';
    public function getDateFormat(): string
    {
        return 'U';
    }

    public function freshTimestamp(): int
    {
        return time();
    }

    public function fromDateTime($value)
    {
        return $value;
    }
}
