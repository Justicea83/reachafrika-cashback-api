<?php

namespace App\Services\Finance\Payment\Flutterwave;
use App\Entities\Payments\Flutterwave\Config\FlutterwaveConfig;
use App\Entities\Payments\Flutterwave\InitiateTransferRequest;
use App\Entities\Response\Payment\Flutterwave\GenericFlutterwaveResponse;
use Illuminate\Support\Facades\Http;


class FlutterwaveService extends FlutterwaveConfig implements IFlutterwaveService
{
    public function initiateTransfer(InitiateTransferRequest $request): GenericFlutterwaveResponse
    {
        // TODO: Implement initiateTransfer() method.
    }

    private function call(array $payload, string $endpoint, string $method = 'post', bool $encrypt = false): GenericFlutterwaveResponse
    {
        $response = Http::asJson()
            ->withToken($this->secretKey)
            ->{strtolower($method)}(
                $endpoint,
                 $payload
            );

        //look here for more info https://developer.flutterwave.com/docs/direct-charge/card/#process
        return GenericFlutterwaveResponse::instance()
            ->setMessage($response->json()['message'] ?? null)
            ->setMeta($response->json()['meta'] ?? null)
            ->setData($response->json()['data'] ?? null)
            ->setStatus($response->json()['status'] ?? null);
    }

    private function encrypt(array $payload): string
    {
        $encrypted = openssl_encrypt(json_encode($payload), 'DES-EDE3', $this->encryptionKey, OPENSSL_RAW_DATA);
        return base64_encode($encrypted);
    }
}
