<?php

namespace App\Entities\Payments\Flutterwave;

use App\Entities\Payments\Flutterwave\Config\FlutterwaveConfig;
use App\Utils\Payments\FlutterwaveUtility;
use Illuminate\Support\Facades\Http;

/**
 * @property $id
 * @property $bank_name
 * @property $subaccount_id
 */
class SubAccount extends FlutterwaveConfig
{
    function __construct()
    {
        parent::__construct();
        $this->url = $this->baseUrl . '/subaccounts';
    }


    public function create(array $data): ?object
    {
        $response = Http::withToken($this->secretKey)->post(
            $this->url,
            $data
        );

        if ($response->successful() && $response->json()['status'] == FlutterwaveUtility::SUCCESS)
            return (object)$response->json()['data'];

        return null;
    }
}
