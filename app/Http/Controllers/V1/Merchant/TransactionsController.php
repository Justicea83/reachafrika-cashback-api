<?php

namespace App\Http\Controllers\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Services\Merchant\Transactions\ITransactionsService;
use App\Utils\Merchant\TransactionsFilterOptions;
use Illuminate\Http\JsonResponse;

class TransactionsController extends Controller
{
    private ITransactionsService $transactionsService;

    function __construct(ITransactionsService $transactionsService)
    {
        $this->transactionsService = $transactionsService;
    }

    public function getTransactions(): JsonResponse
    {
        $filters = new TransactionsFilterOptions(request()->query('page') ?? 1, request()->query('page-size') ?? 25, request()->query('search-query'));

        $filters->setAmountEnd(request()->query('amount-end'))
            ->setAmountStart(request()->query("amount-start"))
            ->setStartDate(request()->query("start-date"))
            ->setEndDate(request()->query("end-date"))
            ->setStatuses(request()->query("statuses"));

        return $this->successResponse(
            $this->transactionsService->getTransactions(
                request()->user(),
                $filters
            )
        );
    }
}
