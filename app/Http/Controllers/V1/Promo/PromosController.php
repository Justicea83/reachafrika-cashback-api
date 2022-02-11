<?php

namespace App\Http\Controllers\V1\Promo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Promo\CreatePromoCampaignRequest;
use App\Http\Requests\Promo\CreatePromoScheduleRequest;
use App\Services\Promo\Campaign\IPromoCampaignService;
use App\Services\Promo\IPromoService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PromosController extends Controller
{
    private IPromoService $promoService;
    private IPromoCampaignService $campaignService;

    function __construct(IPromoService $promoService, IPromoCampaignService $campaignService)
    {
        $this->promoService = $promoService;
        $this->campaignService = $campaignService;
    }

    public function getDays(): JsonResponse
    {
        return $this->successResponse($this->promoService->getDays());
    }

    public function getTime(): JsonResponse
    {
        return $this->successResponse($this->promoService->getTime());
    }

    //schedules
    public function createSchedule(CreatePromoScheduleRequest $request): JsonResponse
    {
        return $this->successResponse($this->promoService->createSchedule($request->user(), $request->only(
            [
                'promo_day_id', 'from', 'to'
            ]
        )));
    }

    public function getSchedules(): JsonResponse
    {
        return $this->successResponse($this->promoService->getSchedules(request()->user()));
    }

    public function getFrequencies(): JsonResponse
    {
        return $this->successResponse($this->promoService->getFrequencies());
    }

    public function toggleScheduleActive(int $id): Response
    {
        $this->promoService->toggleScheduleActive(request()->user(), $id);
        return $this->noContent();
    }

    public function deleteSchedule(int $id): Response
    {
        $this->promoService->deleteSchedule(request()->user(), $id);
        return $this->noContent();
    }

    //campaigns
    public function createPromoCampaign(CreatePromoCampaignRequest $request): JsonResponse
    {
        return $this->successResponse($this->campaignService->createCampaign($request));
    }

    public function getPromoCampaigns(): JsonResponse
    {
        return $this->successResponse($this->campaignService->getCampaigns(request()->user()));
    }

    public function deletePromoCampaign(int $id): Response
    {
        $this->campaignService->deleteCampaign(request()->user(), $id);
        return $this->noContent();
    }
}
