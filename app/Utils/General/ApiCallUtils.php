<?php

namespace App\Utils\General;

class ApiCallUtils
{
    public const METHOD_POST = 'post';
    public const METHOD_GET = 'get';

    public const ALLOWED_METHODS = [
        self::METHOD_POST,
        self::METHOD_GET,
    ];
}
