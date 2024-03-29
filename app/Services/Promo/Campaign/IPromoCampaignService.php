<?php

namespace App\Services\Promo\Campaign;

use App\Dtos\Promo\PromoCampaignDto;
use App\Http\Requests\Promo\CreatePromoCampaignRequest;
use App\Models\User;
use App\Utils\General\FilterOptions;
use Illuminate\Pagination\LengthAwarePaginator;

interface IPromoCampaignService
{
    public function createCampaign(CreatePromoCampaignRequest $request) : PromoCampaignDto;
    public function getCampaigns(User $user,FilterOptions $filterOptions) : LengthAwarePaginator;
    public function deleteCampaign(User $user, int $id);
}
