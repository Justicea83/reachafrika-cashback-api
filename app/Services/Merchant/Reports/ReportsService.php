<?php

namespace App\Services\Merchant\Reports;

use App\Entities\Responses\Reports\Pos\PosSummaryReport;
use App\Models\Finance\PaymentMode;
use App\Models\Finance\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportsService implements IReportsService
{
    private Transaction $transactionModel;

    function __construct(Transaction $transactionModel)
    {
        $this->transactionModel = $transactionModel;
    }

    public function posSummaryReport(User $user, string $startDate, string $endDate): PosSummaryReport
    {
        $response = PosSummaryReport::builder();
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $builder = $this->transactionModel->query()
            ->where('merchant_id', $user->merchant_id)
            ->where('pos_id', $user->pos->id)
            ->where('transaction','credit')
            ->where('created_at', '>=', $start->unix())
            ->when(
                $startDate == $endDate,

                function (Builder $query) use ($end) {
                    $query->where('created_at', '<=', $end->addHours(24)->unix());
                },

                function (Builder $query) use ($end) {
                    $query->where('created_at', '<=', $end->unix());
                }
            );

        $groupedData = $builder->get()->groupBy('payment_mode_id');

        $response->setTotalAmount($builder->sum('amount'))
            ->setEnd($end->toDateTimeString())
            ->setReportTitle("Summary Sales Report")
            ->setUserName($user->fullName)
            ->setFormattedDuration(
                sprintf("From %s to %s", $start->toFormattedDateString(), $end->toFormattedDateString())
            )
            ->setCurrency($user->merchant->country->currency_symbol)
            ->setStart($start->toDateTimeString());

        /** @var Collection $groupedItem */
        foreach ($groupedData->all() as $key => $groupedItem) {
            $response->addPaymentModeSums($this->selectPaymentModeName($key), $groupedItem->sum('amount'));
        }

        return $response;

    }

    private function selectPaymentModeName(int $id): string
    {
        /** @var PaymentMode $paymentMode */
        $paymentMode = DB::table('payment_modes')->where('id', $id)->select('display_name')->first();
        return $paymentMode->display_name;
    }
}
