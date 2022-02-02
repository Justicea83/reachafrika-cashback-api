<?php

namespace App\Services\Merchant\Pos;

use App\Models\User;
use App\Utils\General\FilterOptions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IPosService
{
    public function createPos(User $user, array $payload): Model;

    public function getPos(int $posId): ?Model;

    public function getAllPos(User $user): LengthAwarePaginator;

    public function getMerchantBranchPosByBranchId(User $user, int $branchId): LengthAwarePaginator;

    public function getArchivedPos(User $user): Collection;

    public function undelete(int $id): Model;

    public function getMerchantPosByMerchantId(User $user, int $merchantId): LengthAwarePaginator;

    public function markAsActive(int $id);

    public function markAsBlocked(int $id);

    public function deletePos(int $id);

    public function updatePos(User $user, int $id, array $payload);

    public function markAsPending(int $id);

    public function assignPosToUser(int $id, int $userId);

    public function assignPosToBranch(int $id, int $branchId);

    public function sendApprovalRequest(User $user, array $payload, string $userAgent);

    public function getQrCode(User $user, int $posId): string;

    public function getMyApprovals(User $user, FilterOptions $filterOptions): LengthAwarePaginator;

    public function approvalActionCall(User $user, array $payload);

}
