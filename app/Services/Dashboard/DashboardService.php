<?php

namespace App\Services\Dashboard;

use App\Entities\Responses\Dashboard\OverviewResponse;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService implements IDashboardService
{

    public function getOverview(User $user): OverviewResponse
    {
        $transQuery = DB::select(
            DB::raw("
                SELECT COUNT(*) transactions_count
            FROM transactions trans
            WHERE trans.merchant_id = :merchant_id
            AND trans.transaction_type = 'cashback'
            "),
            [
                'merchant_id' => $user->merchant_id
            ]
        );

        $revQuery = DB::select(
            DB::raw("
                SELECT   CONCAT(c.currency_symbol,' ',FORMAT(SUM(trans.amount),2)) total_revenue
            FROM transactions trans
        INNER JOIN merchants m on trans.merchant_id = m.id
        INNER JOIN countries c on m.country_id = c.id
            WHERE trans.merchant_id = :merchant_id
            AND trans.transaction_type = 'cashback'
        GROUP BY trans.merchant_id

            "),
            [
                'merchant_id' => $user->merchant_id
            ]
        );

        return OverviewResponse::instance()->setTotalTransactions($transQuery[0]->transactions_count)
            ->setPendingSettlement(
                sprintf(
                    "%s %s", $user->merchant->country->currency_symbol,
                    number_format($user->merchant->normalAccount->balance, 2)
                )
            )
            ->setTotalRevenue($revQuery[0]->total_revenue);
    }

    public function branchSummary(User $user): array
    {
        return DB::select(
            DB::raw("
                SELECT DISTINCT  CONCAT(c.currency_symbol,' ',FORMAT(SUM(trans.amount),2)) total_amount,b.name branch, COUNT(*)  transactions_count
            FROM transactions trans
         INNER JOIN branches b on trans.branch_id = b.id
        INNER JOIN merchants m on b.merchant_id = m.id
        INNER JOIN countries c on m.country_id = c.id
        WHERE trans.merchant_id = :merchant_id
        AND trans.transaction_type = 'cashback'
        GROUP BY trans.branch_id
            "),
            [
                'merchant_id' => $user->merchant_id
            ]
        );

    }

    public function posSummary(User $user): array
    {
        return DB::select(
            DB::raw("
            SELECT DISTINCT  CONCAT(c.currency_symbol,' ',FORMAT(SUM(trans.amount),2)) total_amount,b.name branch,p.name pos, COUNT(*)  transactions_count
            FROM transactions trans
            INNER JOIN branches b on trans.branch_id = b.id
            INNER JOIN merchants m on b.merchant_id = m.id
            INNER JOIN countries c on m.country_id = c.id
            INNER JOIN pos p on trans.pos_id = p.id
            WHERE trans.merchant_id = :merchant_id
            AND trans.transaction_type = 'cashback'
            GROUP BY trans.pos_id,trans.branch_id
            "),
            [
                'merchant_id' => $user->merchant_id
            ]
        );
    }
}
