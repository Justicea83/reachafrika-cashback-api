<?php

namespace App\Entities\Responses\Dashboard;

class OverviewResponse
{
    public string $total_revenue;
    public string $total_transactions;
    public ?string $pending_settlement;

    public static function instance(): OverviewResponse
    {
        return new OverviewResponse();
    }

    /**
     * @param string $total_revenue
     * @return OverviewResponse
     */
    public function setTotalRevenue(string $total_revenue): OverviewResponse
    {
        $this->total_revenue = $total_revenue;
        return $this;
    }

    /**
     * @param string $total_transactions
     * @return OverviewResponse
     */
    public function setTotalTransactions(string $total_transactions): OverviewResponse
    {
        $this->total_transactions = $total_transactions;
        return $this;
    }

    /**
     * @param string|null $pending_settlement
     * @return OverviewResponse
     */
    public function setPendingSettlement(?string $pending_settlement): OverviewResponse
    {
        $this->pending_settlement = $pending_settlement;
        return $this;
    }
}
