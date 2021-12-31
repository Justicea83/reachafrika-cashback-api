<?php

namespace App\Services\Merchant\Transactions;

use App\Models\User;
use App\Utils\Merchant\TransactionsFilterOptions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ITransactionsService
{
    public function getTransactions(User $user, TransactionsFilterOptions $filterOptions): LengthAwarePaginator;
}
