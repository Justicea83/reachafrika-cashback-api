<?php

namespace App\Http\Controllers\V1\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Cashback\CreateCashbackRequest;
use App\Services\Settings\Cashback\ICashbackService;

class CashbacksController extends Controller
{
    private ICashbackService $cashbackService;

    function __construct(ICashbackService $cashbackService)
    {
        $this->cashbackService = $cashbackService;
    }

    public function createCashback(CreateCashbackRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse($this->cashbackService->createCashback($request->user(),$request->all()));
    }
}
