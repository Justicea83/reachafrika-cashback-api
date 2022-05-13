<?php

namespace App\Entities\Payments\Paystack;

class TransferReceiptRequest
{
    public string $currency;
    public string $bank_code;
    public string $account_number;
    public string $name;
    public string $type;

    public static function instance(): TransferReceiptRequest
    {
        return new TransferReceiptRequest();
    }

    /**
     * @param string $currency
     * @return TransferReceiptRequest
     */
    public function setCurrency(string $currency): TransferReceiptRequest
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param string $bank_code
     * @return TransferReceiptRequest
     */
    public function setBankCode(string $bank_code): TransferReceiptRequest
    {
        $this->bank_code = $bank_code;
        return $this;
    }

    /**
     * @param string $account_number
     * @return TransferReceiptRequest
     */
    public function setAccountNumber(string $account_number): TransferReceiptRequest
    {
        $this->account_number = $account_number;
        return $this;
    }

    /**
     * @param string $name
     * @return TransferReceiptRequest
     */
    public function setName(string $name): TransferReceiptRequest
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $type
     * @return TransferReceiptRequest
     */
    public function setType(string $type): TransferReceiptRequest
    {
        $this->type = $type;
        return $this;
    }
}
