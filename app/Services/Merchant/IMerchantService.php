<?php

namespace App\Services\Merchant;

use App\Models\Merchant\Merchant;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface IMerchantService
{
    public function setup(?User $user, array $payload);

    public function createMerchant(?User $user, array $payload): Model;

    public function createMerchantBranch(User $user, Merchant $merchant, array $payload): Model;

    public function createMerchantBranchByMerchantId(User $user, array $payload): Model;

    public function createMerchantUserByMerchantId(User $user,  array $payload): Model;

    public function getMerchants(): LengthAwarePaginator;

    public function getMerchantsByStatus(string $status): LengthAwarePaginator;

    public function getMerchant(int $id): Model;

    public function getMyMerchant(User $user): Model;

    public function updateMerchant(User $user, array $payload, int $id);

    public function getMerchantBranches(User $user): LengthAwarePaginator;

    public function getMerchantUsers(User $user): LengthAwarePaginator;

    public function deleteMerchant(User $user);

    public function markAsActive(User $user);

    public function markAsBlocked(User $user);

    public function markAsPending(User $user);

    public function deleteBranch(User $user, $branchId);

    public function changeBranchStatus(User $user,int $branchId, string $status);



}
