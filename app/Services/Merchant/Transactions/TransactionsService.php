<?php

namespace App\Services\Merchant\Transactions;

use App\Dtos\Merchant\Finance\TransactionDto;
use App\Models\Finance\Transaction;
use App\Models\User;
use App\Utils\CashbackUtils;
use App\Utils\Merchant\TransactionsFilterOptions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class TransactionsService implements ITransactionsService
{
    private Transaction $transactionModel;

    function __construct(Transaction $transactionModel)
    {
        $this->transactionModel = $transactionModel;
    }

    public function getTransactions(User $user, TransactionsFilterOptions $filterOptions): LengthAwarePaginator
    {
        $pagedData =
            $this->transactionModel->query()
                ->where('merchant_id', $user->merchant_id)
                ->where('pos_id', $user->pos->id)
                ->where('transaction_type', CashbackUtils::NAME)
                ->when($filterOptions->amountStart, function (Builder $query) use ($filterOptions) {
                    $query->where('amount', '>=', $filterOptions->amountStart);
                })
                ->when($filterOptions->amountEnd, function (Builder $query) use ($filterOptions) {
                    $query->where('amount', '<=', $filterOptions->amountEnd);
                })
                ->when($filterOptions->startDate, function (Builder $query) use ($filterOptions) {
                    $query->where('created_at', '>=', Carbon::parse($filterOptions->startDate)->unix());
                })
                ->when($filterOptions->endDate, function (Builder $query) use ($filterOptions) {
                    $checkDate = Carbon::parse($filterOptions->endDate);
                    if ($filterOptions->startDate == $filterOptions->endDate)
                        $checkDate->addHours(24);
                    $query->where('created_at', '<=', $checkDate->unix());
                })
                ->when($filterOptions->statuses, function (Builder $query) use ($filterOptions) {
                    $statuses = explode(',', $filterOptions->statuses);
                    $query->whereIn('status', $statuses);
                })
                ->latest()
                ->paginate($filterOptions->pageSize, ['*'], 'page', $filterOptions->page);

        $pagedData->getCollection()->transform(function (Transaction $transaction) {
            return TransactionDto::map($transaction);
        });
        return $pagedData;
    }

    public function getTransactionDetail(string $ref): ?TransactionDto
    {
        /** @var Transaction $transaction */
        $transaction = $this->transactionModel->query()->where('id', $ref)->orWhere('reference', $ref)->first();
        if(is_null($transaction)) return null;
        return TransactionDto::map($transaction);
    }
}
