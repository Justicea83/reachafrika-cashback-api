<?php

namespace App\Dtos;

abstract class BaseDto
{
    public array $params = [];
    abstract public function mapFromModel($model, array $params = []);
}

