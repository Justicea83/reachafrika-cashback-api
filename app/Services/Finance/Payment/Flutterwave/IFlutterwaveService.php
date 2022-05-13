<?php

namespace App\Services\Finance\Payment\Flutterwave;

use App\Entities\Payments\Flutterwave\InitiateTransferRequest;
use App\Entities\Response\Payment\Flutterwave\GenericFlutterwaveResponse;

interface IFlutterwaveService
{
    public function initiateTransfer(InitiateTransferRequest $request) : GenericFlutterwaveResponse;
}
