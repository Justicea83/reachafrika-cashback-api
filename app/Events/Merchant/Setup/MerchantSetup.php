<?php

namespace App\Events\Merchant\Setup;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MerchantSetup
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $merchantId;
    public function __construct(int $merchantId)
    {
        $this->merchantId = $merchantId;
    }

}
