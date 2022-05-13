<?php

namespace App\Entities\Responses\Payment\Paystack;

class GenericPaystackResponse
{
    public bool $status = false;
    public string $message;
    public ?array $data;

    public function isSuccessful(): bool
    {
        return $this->status;
    }

    public static function instance(): GenericPaystackResponse
    {
        return new GenericPaystackResponse();
    }

    /**
     * @param bool $status
     * @return GenericPaystackResponse
     */
    public function setStatus(bool $status): GenericPaystackResponse
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param string $message
     * @return GenericPaystackResponse
     */
    public function setMessage(string $message): GenericPaystackResponse
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param array|null $data
     * @return GenericPaystackResponse
     */
    public function setData(?array $data): GenericPaystackResponse
    {
        $this->data = $data;
        return $this;
    }
}
