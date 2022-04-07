<?php

namespace App\Entities\Payments\Paystack\Config;

use function config;

abstract class PaystackConfig
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

        $this->publicKey = config('paystack.publicKey');
        $this->secretKey = config('paystack.secretKey');
        $this->baseUrl = config('paystack.paymentUrl');
    }
}
