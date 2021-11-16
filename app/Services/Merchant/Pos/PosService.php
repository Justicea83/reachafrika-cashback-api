<?php

namespace App\Services\Merchant\Pos;

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

    function __construct(
        Pos $posModel
    )
    {
        $this->posModel = $posModel;
    }

    /**
     * @throws Exception
     */
    public function createPos(User $user, array $payload): Model
    {
        if ($user->merchant_id == null) throw new InvalidArgumentException("you cannot create a pos because you don't have a merchant");
        $payload['created_by'] = $user->id;
        $payload['merchant_id'] = $user->merchant_id;
        $payload['code'] =$this->getCodeForPos();

        /** @var Pos $pos */
        $pos = $this->posModel->query()->create($payload);
        return $this->getPos($pos->id);
    }

    /**
     * @throws Exception
     */
    private function getCodeForPos(): string{
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
            ->where('merchant_id',$user->merchant_id)
            ->where('branch_id', $branchId)
            ->paginate($pageSize, ['*'], 'page', $page);
    }

    public function getAllPos(User $user): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        return $this->posModel->query()
            ->where('merchant_id',$user->merchant_id)
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
            ->where('merchant_id',$user->merchant_id)
            ->get();
    }

    public function undelete(int $id) : Model
    {
        $this->posModel->query()->withTrashed()->find($id)->restore();
        return $this->getPos($id);
    }
}
