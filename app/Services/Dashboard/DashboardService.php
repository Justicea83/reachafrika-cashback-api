<?php

namespace App\Services\Dashboard;

use App\Entities\Responses\Dashboard\OverviewResponse;
use App\Models\Finance\Transaction;
use App\Models\User;
use App\Utils\CashbackUtils;
use App\Utils\Status;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class DashboardService implements IDashboardService
{
    private Transaction $transactionModel;

    function __construct(Transaction $transactionModel)
    {
        $this->transactionModel = $transactionModel;
    }
    public function getOverview(User $user): OverviewResponse
    {
        $transQuery = DB::select(
            DB::raw("
                SELECT COUNT(*) transactions_count
            FROM transactions trans
            WHERE trans.merchant_id = :merchant_id
            AND trans.transaction_type = :transaction_type
            "),
            [
                'merchant_id' => $user->merchant_id,
                'transaction_type' => CashbackUtils::NAME
            ]
        );

        $revQuery = DB::select(
            DB::raw("
                SELECT   CONCAT(c.currency_symbol,' ',FORMAT(SUM(trans.amount),2)) total_revenue
                FROM transactions trans
                INNER JOIN merchants m on trans.merchant_id = m.id
                INNER JOIN countries c on m.country_id = c.id
                WHERE trans.merchant_id = :merchant_id
                AND trans.transaction_type = :transaction_type
                GROUP BY trans.merchant_id
            "),
            [
                'merchant_id' => $user->merchant_id,
                'transaction_type' => CashbackUtils::NAME
            ]
        );

        return OverviewResponse::instance()->setTotalTransactions($transQuery[0]->transactions_count)
            ->setPendingSettlement(
                sprintf(
                    "%s %s", $user->merchant->country->currency_symbol,
                    number_format($user->merchant->normalAccount->balance, 2)
                )
            )
            ->setTotalRevenue(!is_null($revQuery) && count($revQuery) > 0 ? $revQuery[0]->total_revenue : 0);
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
        AND trans.transaction_type = :transaction_type
        GROUP BY trans.branch_id
        ORDER BY total_amount DESC
        LIMIT 10
            "),
            [
                'merchant_id' => $user->merchant_id,
                'transaction_type' => CashbackUtils::NAME
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
            AND trans.transaction_type = :transaction_type
            GROUP BY trans.pos_id,trans.branch_id
            ORDER BY total_amount DESC
            LIMIT 10
            "),
            [
                'merchant_id' => $user->merchant_id,
                'transaction_type' => CashbackUtils::NAME
            ]
        );
    }

    /**
     * @param User $user
     * @param string $mode General,Branch, POS
     * @param string|null $start
     * @param string|null $end
     * @param array $options
     * @return array
     */
    public function getGraphData(User $user, string $mode, array $options = [], ?string $start = null, ?string $end = null): array
    {
        if (is_null($start)) {
            $startDate = now()->startOfYear();
        } else {
            $startDate = Carbon::parse($start);
        }
        if (is_null($end)) {
            $endDate = now()->endOfYear();
        } else {
            $endDate = Carbon::parse($end);
        }
        switch ($mode){
            case 'general':
                $builder =  $this->transactionModel->query()
                    ->where('merchant_id', $user->merchant_id);
                    break;
            case 'branch':
                $builder =  $this->transactionModel->query()
                    ->where('branch_id', $options['branch_id'])
                    ->where('merchant_id', $user->merchant_id);
                break;
            case 'pos':
                $builder =  $this->transactionModel->query()
                    ->where('pos_id', $options['pos_id'])
                    ->where('merchant_id', $user->merchant_id);
                break;
            default:
                throw new InvalidArgumentException('Please specify the mode fo the graph data');
        }
        return $builder
            ->where('transaction_type', CashbackUtils::NAME)
            ->where('status', Status::STATUS_COMPLETED)
            ->where('created_at', '>=', $startDate->unix())
            ->where('created_at', '<=', $endDate->unix())
            ->selectRaw('SUM(amount) as total, MONTHNAME(FROM_UNIXTIME(created_at)) AS month_name,MONTH(FROM_UNIXTIME(created_at)) AS month, YEAR(FROM_UNIXTIME(created_at)) AS year')
            ->groupBy(DB::raw('month'))
            ->groupBy(DB::raw('month_name'))
            ->groupBy(DB::raw('year'))
            ->get()
            ->map(fn($transaction) => [
                $transaction['month_name'] => $transaction['total']
            ])
            ->toArray();
    }
}
