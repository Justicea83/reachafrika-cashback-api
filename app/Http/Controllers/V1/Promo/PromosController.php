<?php

namespace App\Http\Controllers\V1\Promo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Promo\CreatePromoScheduleRequest;
use App\Services\Promo\IPromoService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PromosController extends Controller
{
    private IPromoService $promoService;

    function __construct(IPromoService $promoService)
    {
        $this->promoService = $promoService;
    }

    public function getDays(): JsonResponse
    {
        return $this->successResponse($this->promoService->getDays());
    }

    public function getTime(): JsonResponse
    {
        return $this->successResponse($this->promoService->getTime());
    }

    public function createSchedule(CreatePromoScheduleRequest $request): JsonResponse
    {
        return $this->successResponse($this->promoService->createSchedule($request->user(),$request->only(
            [
                'promo_day_id', 'from' , 'to'
            ]
        )));
    }

    public function getSchedules(): JsonResponse
    {
        return $this->successResponse($this->promoService->getSchedules(request()->user()));
    }

    public function toggleScheduleActive(int $id): Response
    {
        $this->promoService->toggleScheduleActive(request()->user(),$id);
        return $this->noContent();
    }

    public function deleteSchedule(int $id): Response
    {
        $this->promoService->deleteSchedule(request()->user(),$id);
        return $this->noContent();
    }
}
