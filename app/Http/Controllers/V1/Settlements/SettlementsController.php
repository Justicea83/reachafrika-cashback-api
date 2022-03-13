<?php

namespace App\Http\Controllers\V1\Settlements;

use App\Events\Merchant\Setup\MerchantSetup;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class SettlementsController extends Controller
{
    public function setupSubAccounts(int $merchantId): Response
    {
        MerchantSetup::dispatch($merchantId);
        return $this->noContent();
    }
}
