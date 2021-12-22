<?php

namespace App\Http\Controllers\V1\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Cashback\CreateCashbackRequest;
use App\Http\Requests\Settings\Cashback\UpdateCashbackRequest;
use App\Services\Settings\Cashback\ICashbackService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CashbacksController extends Controller
{
    private ICashbackService $cashbackService;

    function __construct(ICashbackService $cashbackService)
    {
        $this->cashbackService = $cashbackService;
    }

    public function createCashback(CreateCashbackRequest $request): JsonResponse
    {
        return $this->successResponse($this->cashbackService->createCashback($request->user(),$request->all()), Response::HTTP_CREATED);
    }

    public function getCashbacks(): JsonResponse
    {
        return $this->successResponse($this->cashbackService->getCashbacks(request()->user()));
    }

    public function getCashback(int $id): JsonResponse
    {
        return $this->successResponse($this->cashbackService->getCashback(request()->user(),$id));
    }

    public function undeleteCashback(int $id): JsonResponse
    {
        return $this->successResponse($this->cashbackService->unDeleteCashback(request()->user(),$id));
    }

    public function getTrashedCashbacks(CreateCashbackRequest $request): JsonResponse
    {
        return $this->successResponse($this->cashbackService->getDeletedCashbacks($request->user()));
    }

    public function updateCashback(UpdateCashbackRequest $request,int $id): Response
    {
        $this->cashbackService->updateCashback($request->user(),$id,$request->all());
        return $this->noContent();
    }

    public function deleteCashback(int $id): Response
    {
        $this->cashbackService->deleteCashback(request()->user(),$id);
        return $this->noContent();
    }
}
