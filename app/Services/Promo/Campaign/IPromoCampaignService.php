<?php

namespace App\Services\Promo\Campaign;

use App\Dtos\Promo\PromoCampaignDto;
use App\Http\Requests\Promo\CreatePromoCampaignRequest;
use App\Http\Requests\Promo\GetTargetCountRequest;
use App\Models\User;
use App\Utils\General\FilterOptions;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface IPromoCampaignService
{
    public function createCampaign(CreatePromoCampaignRequest $request) : PromoCampaignDto;
    public function getCampaigns(User $user,FilterOptions $filterOptions) : LengthAwarePaginator;
    public function deleteCampaign(User $user, int $id);
    public function pauseCampaign(User $user, int $id);
    public function getImpressionsByBudget(User $user, float $budget) : int;
    public function getTargetCount(GetTargetCountRequest $request) : int;
    public function getPotentialCount(User $user) : int;
    public function getCampaign(User $user, int $id) : PromoCampaignDto;
    public function downloadBlob(string $path) : StreamedResponse;
    public function streamUrl(string $path) : string;
    public function schedule();
    public function processCampaigns();
}
