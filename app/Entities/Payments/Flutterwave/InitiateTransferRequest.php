<?php

namespace App\Entities\Payments\Flutterwave;

class InitiateTransferRequest
{
    public string $account_number;
    public string $account_bank;
    public string $beneficiary_name;
    public float $amount;
    public string $currency;
    public string $reference;
    public string $narration;
    public string $destination_branch_code;
    public array $meta = [];

    public static function instance(): InitiateTransferRequest
    {
        return new InitiateTransferRequest();
    }

    /**
     * @param string $account_number
     * @return InitiateTransferRequest
     */
    public function setAccountNumber(string $account_number): InitiateTransferRequest
    {
        $this->account_number = $account_number;
        return $this;
    }

    /**
     * @param string $account_bank
     * @return InitiateTransferRequest
     */
    public function setAccountBank(string $account_bank): InitiateTransferRequest
    {
        $this->account_bank = $account_bank;
        return $this;
    }

    /**
     * @param string $beneficiary_name
     * @return InitiateTransferRequest
     */
    public function setBeneficiaryName(string $beneficiary_name): InitiateTransferRequest
    {
        $this->beneficiary_name = $beneficiary_name;
        return $this;
    }

    // For M- Pesa Momo
    public function setSender(string $sender): InitiateTransferRequest
    {
        $this->meta['sender'] = $sender;
        return $this;
    }

    // For M- Pesa Momo
    public function setSenderCountry(string $sender_country): InitiateTransferRequest
    {
        $this->meta['sender_country'] = $sender_country;
        return $this;
    }

    // For M- Pesa Momo
    public function setSenderMobileNumber(string $sender_mobile_number): InitiateTransferRequest
    {
        $this->meta['mobile_number'] = $sender_mobile_number;
        return $this;
    }

    /**
     * @param float $amount
     * @return InitiateTransferRequest
     */
    public function setAmount(float $amount): InitiateTransferRequest
    {
        $this->amount = $amount;
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

    /**
     * @param string $narration
     * @return InitiateTransferRequest
     */
    public function setNarration(string $narration): InitiateTransferRequest
    {
        $this->narration = $narration;
        return $this;
    }

    /**
     * @param string $destination_branch_code
     * @return InitiateTransferRequest
     */
    public function setDestinationBranchCode(string $destination_branch_code): InitiateTransferRequest
    {
        $this->destination_branch_code = $destination_branch_code;
        return $this;
    }
}
