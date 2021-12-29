<?php

namespace App\Services\Merchant\Pos;

use App\Models\Merchant\ApprovalRequest;
use App\Models\Merchant\Pos;
use App\Models\User;
use App\Utils\MerchantUtils;
use Exception;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class PosService implements IPosService
{
    private Pos $posModel;
    private ApprovalRequest $approvalRequestModel;

    function __construct(
        Pos             $posModel,
        ApprovalRequest $approvalRequestModel
    )
    {
        $this->posModel = $posModel;
        $this->approvalRequestModel = $approvalRequestModel;
    }

    /**
     * @throws Exception
     */
    public function createPos(User $user, array $payload): Model
    {
        if ($user->merchant_id == null) throw new InvalidArgumentException("you cannot create a pos because you don't have a merchant");

        if ($this->posModel->query()->where('user_id', $user->id)->count() > 1) throw new InvalidArgumentException("this user is already assigned to POS");

        $payload['created_by'] = $user->id;
        $payload['merchant_id'] = $user->merchant_id;
        $payload['code'] = $this->getCodeForPos();

        /** @var Pos $pos */
        $pos = $this->posModel->query()->create($payload);
        return $this->getPos($pos->id);
    }

    /**
     * @throws Exception
     */
    private function getCodeForPos(): string
    {
        do {
            $code = random_int(1000, 99999999);
        } while ($this->posModel->query()->where("code", $code)->first());

        return (string)$code;
    }

    public function getMerchantBranchPosByBranchId(User $user, int $branchId): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        return $this->posModel->query()
            ->where('merchant_id', $user->merchant_id)
            ->where('branch_id', $branchId)
            ->paginate($pageSize, ['*'], 'page', $page);
    }

    public function getAllPos(User $user): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        return $this->posModel->query()
            ->where('merchant_id', $user->merchant_id)
            ->paginate($pageSize, ['*'], 'page', $page);
    }

    public function getMerchantPosByMerchantId(User $user, int $merchantId): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        return $this->posModel->query()
            ->where('merchant_id', $merchantId)
            ->paginate($pageSize, ['*'], 'page', $page);
    }

    private function updatePosStatus(int $id, string $status)
    {
        /** @var Pos $pos */
        $pos = $this->getPos($id);
        if ($pos != null) {
            $pos->status = $status;
            $pos->save();
        }
    }

    public function markAsActive(int $id)
    {
        $this->updatePosStatus($id, MerchantUtils::MERCHANT_STATUS_ACTIVE);

    }

    public function markAsBlocked(int $id)
    {
        $this->updatePosStatus($id, MerchantUtils::MERCHANT_STATUS_SUSPENDED);

    }

    public function markAsPending(int $id)
    {
        $this->updatePosStatus($id, MerchantUtils::MERCHANT_STATUS_PENDING);
    }

    public function deletePos(int $id)
    {
        $this->posModel->query()->find($id)->delete();
    }

    public function getPos(int $posId): Model
    {
        return $this->posModel->query()->with(['user', 'branch'])->find($posId);
    }

    public function updatePos(User $user, int $id, array $payload)
    {
        $this->posModel->query()->find($id)->update([
            'name' => $payload['name']
        ]);
    }

    public function assignPosToUser(int $id, int $userId)
    {
        $this->posModel->query()->find($id)->update([
            'user_id' => $userId
        ]);
    }

    public function assignPosToBranch(int $id, int $branchId)
    {
        $this->posModel->query()->find($id)->update([
            'branch_id' => $branchId
        ]);
    }

    public function getArchivedPos(User $user): Collection
    {
        return $this->posModel->query()
            ->onlyTrashed()
            ->where('merchant_id', $user->merchant_id)
            ->get();
    }

    public function undelete(int $id): Model
    {
        $this->posModel->query()->withTrashed()->find($id)->restore();
        return $this->getPos($id);
    }

    public function sendApprovalRequest(User $user, array $payload, string $userAgent)
    {
        $pos = $user->pos;
        $country = $user->merchant->country;
        if ($pos == null) throw new InvalidArgumentException('this users has not been assigned a POS');
        if ($country == null) throw new InvalidArgumentException('this merchant needs to be assigned to a country');

        $this->approvalRequestModel->query()->create([
            'pos_id' => $pos->id,
            'phone' => $payload['phone'],
            'amount' => $payload['amount'],
            'currency' => $country->currency,
            'currency_symbol' => $country->currency_symbol,
            'created_by' => $user->id,
            'extra_info'=> json_encode(
                [
                    'platform' => $userAgent
                ]
            )
        ]);

        //TODO send broadcasts here
    }
}
