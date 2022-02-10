<?php

namespace App\Utils\Merchant;

use App\Utils\General\FilterOptions;

class TransactionsFilterOptions extends FilterOptions
{
    public ?string $startDate;
    public ?string $endDate;
    public ?float $amountStart;
    public ?float $amountEnd;
    public ?string $statuses;

    /**
     * @param string|null $startDate
     * @return TransactionsFilterOptions
     */
    public function setStartDate(?string $startDate): TransactionsFilterOptions
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @param string|null $endDate
     * @return TransactionsFilterOptions
     */
    public function setEndDate(?string $endDate): TransactionsFilterOptions
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @param float|null $amountStart
     * @return TransactionsFilterOptions
     */
    public function setAmountStart(?float $amountStart): TransactionsFilterOptions
    {
        $this->amountStart = $amountStart;
        return $this;
    }

    /**
     * @param float|null $amountEnd
     * @return TransactionsFilterOptions
     */
    public function setAmountEnd(?float $amountEnd): TransactionsFilterOptions
    {
        $this->amountEnd = $amountEnd;
        return $this;
    }

    /**
     * @param string|null $statuses
     * @return TransactionsFilterOptions
     */
    public function setStatuses(?string $statuses): TransactionsFilterOptions
    {
        $this->statuses = $statuses;
        return $this;
    }

}
