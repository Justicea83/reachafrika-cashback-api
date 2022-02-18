<?php

namespace App\Http\Controllers\V1\Promo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Promo\CreatePromoCampaignRequest;
use App\Http\Requests\Promo\CreatePromoScheduleRequest;
use App\Http\Requests\Promo\GetImpressionsByBudgetRequest;
use App\Http\Requests\Promo\GetTargetCountRequest;
use App\Services\Promo\Campaign\IPromoCampaignService;
use App\Services\Promo\IPromoService;
use App\Utils\General\FilterOptions;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        return $this->successResponse($this->campaignService->createCampaign($request), Response::HTTP_CREATED);
    }

    public function getPromoCampaigns(): JsonResponse
    {
        $filters = new FilterOptions(request()->query('page') ?? 1, request()->query('page-size') ?? 25, request()->query('search-query'));

        return $this->successResponse(
            $this->campaignService->getCampaigns(
                request()->user(),
                $filters
            )
        );
    }

    public function deletePromoCampaign(int $id): Response
    {
        $this->campaignService->deleteCampaign(request()->user(), $id);
        return $this->noContent();
    }

    public function getPromoCampaign(int $id): Response
    {
        return $this->successResponse($this->campaignService->getCampaign(request()->user(), $id));
    }

    public function getPotentialCount(): Response
    {
        return $this->successResponse($this->campaignService->getPotentialCount(request()->user()));
    }

    public function getTargetCount(GetTargetCountRequest $request): Response
    {
        return $this->successResponse($this->campaignService->getTargetCount($request));
    }

    public function getImpressionsByBudget(GetImpressionsByBudgetRequest $request): Response
    {
        return $this->successResponse($this->campaignService->getImpressionsByBudget(request()->user(), $request['budget']));
    }

    public function downloadBlob(string $path): StreamedResponse
    {
        return $this->campaignService->downloadBlob($path);
    }

    public function pausePromoCampaign(int $id): Response
    {
        $this->campaignService->pauseCampaign(request()->user(), $id);
        return $this->noContent();
    }
}
