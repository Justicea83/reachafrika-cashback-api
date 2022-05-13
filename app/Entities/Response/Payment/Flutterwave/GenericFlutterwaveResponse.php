<?php

namespace App\Entities\Response\Payment\Flutterwave;

use App\Utils\Payments\Flutterwave\FlutterwaveUtility;

class GenericFlutterwaveResponse
{
    public ?string $status;
    public ?string $meta;
    public ?string $message;
    public ?array $data;

    public function isSuccessful(): bool
    {
        return $this->status === FlutterwaveUtility::SUCCESS;
    }

    public static function instance(): GenericFlutterwaveResponse
    {
        return new GenericFlutterwaveResponse();
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): GenericFlutterwaveResponse
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param mixed $meta
     * @return GenericFlutterwaveResponse
     */
    public function setMeta($meta): GenericFlutterwaveResponse
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @param mixed $message
     * @return GenericFlutterwaveResponse
     */
    public function setMessage($message): GenericFlutterwaveResponse
    {
        $this->message = $message;
        return $this;
    }


    /**
     * @param mixed $data
     * @return GenericFlutterwaveResponse
     */
    public function setData($data): GenericFlutterwaveResponse
    {
        $this->data = $data;
        return $this;
    }
}

