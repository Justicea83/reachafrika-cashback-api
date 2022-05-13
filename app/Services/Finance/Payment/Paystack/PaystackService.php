<?php

namespace App\Services\Finance\Payment\Paystack;

use App\Entities\Payments\Paystack\Config\PaystackConfig;
use App\Entities\Payments\Paystack\InitiateTransferRequest;
use App\Entities\Payments\Paystack\TransferReceiptRequest;
use App\Entities\Responses\Payment\Paystack\GenericPaystackResponse;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService extends PaystackConfig implements IPaystackService
{

    public function createTransferReceipt(TransferReceiptRequest $request): GenericPaystackResponse
    {
        return $this->call((array)$request, $this->baseUrl . '/transferrecipient');
    }

    private function call(array $payload, string $endpoint, string $method = 'post'): GenericPaystackResponse
    {
        /** @var Response $response */
        $response = Http::asJson()
            ->withToken($this->secretKey)
            ->{strtolower($method)}(
                $endpoint, $payload
            );
        Log::info("============================================================= Paystack Call ======================================================================================================================");
        Log::info(get_class(), ['req' => $payload]);
        Log::info(get_class(), ['res' => $response->body()]);
        return GenericPaystackResponse::instance()
            ->setMessage($response->json()['message'] ?? null)
            ->setData($response->json()['data'] ?? null)
            ->setStatus($response->json()['status'] ?? false);
    }


    public function initiateTransfer(InitiateTransferRequest $request): GenericPaystackResponse
    {
        return $this->call((array)$request, $this->baseUrl . '/transfer');
    }
}
