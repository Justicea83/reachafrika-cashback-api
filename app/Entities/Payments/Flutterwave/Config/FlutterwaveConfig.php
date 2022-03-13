<?php

namespace App\Entities\Payments\Flutterwave\Config;

abstract class FlutterwaveConfig
{
    protected string $publicKey;
    protected string $secretKey;
    protected string $baseUrl;
    protected string $url;

    /**
     * Construct
     */
    function __construct()
    {

        $this->publicKey = config('flutterwave.publicKey');
        $this->secretKey = config('flutterwave.secretKey');
        $this->baseUrl = config('flutterwave.baseUrl');
    }

}
