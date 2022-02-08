<?php

namespace App\Entities\Responses\Reports\Pos;

class PosSummaryReport
{
    public static function builder(): PosSummaryReport
    {
        return new PosSummaryReport();
    }
    public array $paymentModeSums = [];
    public float $totalAmount;
    public string $start;
    public string $end;
    public string $reportTitle;
    public string $userName;
    public string $currency;
    public string $formattedDuration;

    /**
     * @param array $paymentModeSums
     * @return PosSummaryReport
     */
    public function setPaymentModeSums(array $paymentModeSums): PosSummaryReport
    {
        $this->paymentModeSums = $paymentModeSums;
        return $this;
    }

    public function addPaymentModeSums(string $key,float $amount): PosSummaryReport
    {
        $this->paymentModeSums[$key] = $amount;
        return $this;
    }

    /**
     * @param float $totalAmount
     * @return PosSummaryReport
     */
    public function setTotalAmount(float $totalAmount): PosSummaryReport
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    /**
     * @param string $end
     * @return PosSummaryReport
     */
    public function setEnd(string $end): PosSummaryReport
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @param string $start
     * @return PosSummaryReport
     */
    public function setStart(string $start): PosSummaryReport
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @param string $reportTitle
     * @return PosSummaryReport
     */
    public function setReportTitle(string $reportTitle): PosSummaryReport
    {
        $this->reportTitle = $reportTitle;
        return $this;
    }

    /**
     * @param string $userName
     * @return PosSummaryReport
     */
    public function setUserName(string $userName): PosSummaryReport
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @param string $currency
     * @return PosSummaryReport
     */
    public function setCurrency(string $currency): PosSummaryReport
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @param string $formattedDuration
     * @return PosSummaryReport
     */
    public function setFormattedDuration(string $formattedDuration): PosSummaryReport
    {
        $this->formattedDuration = $formattedDuration;
        return $this;
    }
}
