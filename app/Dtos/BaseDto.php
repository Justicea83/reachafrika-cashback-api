<?php

namespace App\Dtos;

abstract class BaseDto
{
    abstract public function mapFromModel($model);
}

