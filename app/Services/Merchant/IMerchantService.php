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

    public function createMerchantBranchByMerchantId(User $user, int $merchantId, array $payload): Model;

    public function createMerchantUserByMerchantId(User $user, int $merchantId, array $payload): Model;

    public function getMerchants(): LengthAwarePaginator;

    public function getMerchantsByStatus(string $status): LengthAwarePaginator;

    public function getMerchant(int $id): Model;

    public function updateMerchant(User $user, array $payload, int $id);

    public function getMerchantBranches(int $id): LengthAwarePaginator;

    public function getMerchantUsers(int $id): LengthAwarePaginator;

    public function deleteMerchant(int $id);

    public function markAsActive(int $id);

    public function markAsBlocked(int $id);

    public function markAsPending(int $id);

}
