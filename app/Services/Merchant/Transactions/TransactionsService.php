<?php

namespace App\Services\Merchant\Transactions;

use App\Dtos\Merchant\Finance\TransactionDto;
use App\Models\Finance\Transaction;
use App\Models\User;
use App\Utils\Merchant\TransactionsFilterOptions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransactionsService implements ITransactionsService
{
    private Transaction $transactionModel;

    function __construct(Transaction $transactionModel)
    {
        $this->transactionModel = $transactionModel;
    }

    public function getTransactions(User $user, TransactionsFilterOptions $filterOptions): LengthAwarePaginator
    {
       $pagedData =  $this->transactionModel->query()->where('merchant_id', $user->merchant_id)
           ->latest()
            ->paginate($filterOptions->pageSize, ['*'], 'page', $filterOptions->page);

        $pagedData->getCollection()->transform(function (Transaction $transaction) {
            return TransactionDto::map($transaction);
        });
       return $pagedData;
    }
}
