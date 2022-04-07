<?php

namespace App\Entities\Payments\Paystack;

use App\Entities\Payments\Paystack\Config\PaystackConfig;
use Illuminate\Support\Facades\Http;

class Bank extends PaystackConfig
{
    function __construct()
    {
        parent::__construct();
        $this->url = $this->baseUrl . '/bank';
    }

    public function fetchAll(string $country) : array
    {
        $response = Http::withToken($this->secretKey)->get($this->url, [
            'country' => $country
        ]);

        if ($response->successful() && $response->json()['status'])
            return $response->json()['data'];

        return [];
    }

    public static function instance(): Bank
    {
        return new Bank();
    }
}
