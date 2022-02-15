<?php

namespace App\Services\Promo\Campaign;

use App\Dtos\Promo\PromoCampaignDto;
use App\Http\Requests\Promo\CreatePromoCampaignRequest;
use App\Http\Requests\Promo\GetTargetCountRequest;
use App\Models\User;
use App\Utils\General\FilterOptions;
use Illuminate\Pagination\LengthAwarePaginator;

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
    //TODO endpoint for campaign statistics
}
