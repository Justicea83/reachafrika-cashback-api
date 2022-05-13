<?php

namespace App\Entities\Payments\Paystack;

use App\Entities\Payments\Paystack\Config\PaystackConfig;
use App\Utils\Payments\Paystack\PaystackUtility;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Bank extends PaystackConfig
{
    function __construct()
    {
        parent::__construct();
        $this->url = $this->baseUrl . '/bank';
    }

    public function fetchAll(string $country) : array
    {
        $key = Str::of(sprintf('countries_%s', $country))->prepend(PaystackUtility::CACHE_PREFIX);

        if (Cache::has($key)) {
            return Cache::get($key);
        } else {

            $response = Http::withToken($this->secretKey)->get($this->url, [
                'country' => $country
            ]);

            if ($response->successful() && $response->json()['status']){
                $data =  $response->json()['data'];
            }else{
                $data = [];
            }
            Cache::put($key, $data, now()->addHours(24));
        }

        return $data;
    }

    public static function instance(): Bank
    {
        return new Bank();
    }

    public function resolve(string $accountNo, string $code){
        $response = Http::withToken($this->secretKey)->get($this->url . '/resolve', [
            'bank_code' => $code,
            'account_number' => $accountNo,
        ]);
        if ($response->successful() && $response->json()['status'])
            return $response->json()['data'];

        return [];
    }
}
