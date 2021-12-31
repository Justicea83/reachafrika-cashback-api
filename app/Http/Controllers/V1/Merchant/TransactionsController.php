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
        return $this->successResponse(
            $this->transactionsService->getTransactions(
                request()->user(),
                new TransactionsFilterOptions(request()->query('page') ?? 1, request()->query('page-size') ?? 25, request()->query('search-query'))
            )
        );
    }
}
