<?php

namespace App\Entities\Payments\Paystack;

class InitiateTransferRequest
{
    public string $reason;
    public string $recipient;
    public int $amount;
    public string $source = 'balance';
    public string $currency;
    public string $reference;


    public static function instance(): InitiateTransferRequest
    {
        return new InitiateTransferRequest();
    }

    /**
     * @param string $reason
     * @return InitiateTransferRequest
     */
    public function setReason(string $reason): InitiateTransferRequest
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @param string $recipient
     * @return InitiateTransferRequest
     */
    public function setRecipient(string $recipient): InitiateTransferRequest
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @param int $amount
     * @return InitiateTransferRequest
     */
    public function setAmount(int $amount): InitiateTransferRequest
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param string $source
     * @return InitiateTransferRequest
     */
    public function setSource(string $source): InitiateTransferRequest
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @param string $currency
     * @return InitiateTransferRequest
     */
    public function setCurrency(string $currency): InitiateTransferRequest
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param string $reference
     * @return InitiateTransferRequest
     */
    public function setReference(string $reference): InitiateTransferRequest
    {
        $this->reference = $reference;
        return $this;
    }
}
