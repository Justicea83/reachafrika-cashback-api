<?php

namespace App\Services\Finance\Payment\Paystack;

use App\Entities\Payments\Paystack\TransferReceiptRequest;
use App\Entities\Payments\Paystack\InitiateTransferRequest;
use App\Entities\Responses\Payment\Paystack\GenericPaystackResponse;

interface IPaystackService
{
    public function createTransferReceipt(TransferReceiptRequest $request) : GenericPaystackResponse;
    public function initiateTransfer(InitiateTransferRequest $request) : GenericPaystackResponse;
}
