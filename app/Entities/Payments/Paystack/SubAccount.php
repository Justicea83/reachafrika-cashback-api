<?php

namespace App\Entities\Payments\Paystack;

use App\Entities\Payments\Paystack\Config\PaystackConfig;
use Illuminate\Support\Facades\Http;

/**
 * @property $id
 * @property $subaccount_code
 * @property $settlement_bank
 */
class SubAccount extends PaystackConfig
{
    function __construct()
    {
        parent::__construct();
        $this->url = $this->baseUrl . '/subaccount';
    }

    public function create(array $data): ?object
    {
        $response = Http::withToken($this->secretKey)->post(
            $this->url,
            $data
        );

        if ($response->successful() && $response->json()['status'])
            return (object)$response->json()['data'];

        return null;
    }

    public static function instance(): SubAccount
    {
        return new SubAccount();
    }
}
