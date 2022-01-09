<?php

namespace App\Services\Merchant;

use App\Models\Merchant\Branch;
use App\Models\Merchant\Merchant;
use App\Models\User;
use App\Services\UserManagement\IUserManagementService;
use App\Utils\MerchantUtils;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class MerchantService implements IMerchantService
{
    private IUserManagementService $userManagementService;
    private Merchant $merchantModel;
    private Branch $branchModel;

    function __construct(
        IUserManagementService $userManagementService,
        Merchant               $merchantModel,
        Branch                 $branchModel
    )
    {
        $this->userManagementService = $userManagementService;
        $this->merchantModel = $merchantModel;
        $this->branchModel = $branchModel;
    }

    /**
     * @throws Exception
     */
    public function setup(?User $user, array $payload)
    {
        [
            'user' => $userData,
            'merchant' => $merchant
        ] = $payload;

        //create merchant
        /** @var Merchant $createdMerchant */
        $createdMerchant = $this->createMerchant($user, $merchant);


        /** @var User $createdUser */
        $userData['merchant_id'] = $createdMerchant->id;
        $this->userManagementService->createUser($userData);
    }

    /**
     * @throws Exception
     */
    public function createMerchant(?User $user, array $payload): ?Model
    {
        $merchant = null;
        if ($user != null)
            $payload['created_by'] = $user->id;
        $payload['code'] = $this->getCodeForMerchant();

        DB::beginTransaction();

        try {
            /** @var Merchant $merchant */
            $merchant = $this->merchantModel->query()->create($payload);
            $merchant->account()->create();
        } catch (Exception $e) {
            DB::rollBack();
        }

        DB::commit();
        return $merchant;
    }

    /**
     * @throws Exception
     */
    private function getCodeForMerchant(): string
    {
        do {
            $code = random_int(1000, 999999);
        } while ($this->merchantModel->query()->where("code", $code)->first());

        return (string)$code;
    }

    /**
     * @throws Exception
     */
    private function getCodeForMerchantBranch(): string
    {
        do {
            $code = random_int(1000, 99999999);
        } while ($this->branchModel->query()->where("code", $code)->first());

        return (string)$code;
    }

    public function createMerchantBranch(User $user, Merchant $merchant, array $payload): Model
    {
        $payload['created_by'] = $user->id;
        return $merchant->branches()->create($payload);
    }

    /**
     * @throws Exception
     */
    public function createMerchantBranchByMerchantId(User $user, array $payload): Model
    {
        /** @var Merchant $merchant */
        $merchant = $this->merchantModel->query()->find($user->merchant_id);
        if ($merchant == null) throw new InvalidArgumentException("merchant not found");
        $payload['code'] = $this->getCodeForMerchantBranch();
        $payload['created_by'] = $user->id;
        return $this->createMerchantBranch($user, $merchant, $payload);
    }

    public function createMerchantUserByMerchantId(User $user, array $payload): Model
    {
        /** @var Merchant $merchant */
        $merchant = $this->merchantModel->query()->find($user->merchant_id);
        if ($merchant == null) throw new InvalidArgumentException("merchant not found");
        $payload['merchant_id'] = $user->merchant_id;
        $payload['created_by'] = $user->id;
        return $this->userManagementService->createUser($payload);
    }

    public function getMerchants(): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        return $this->merchantModel->query()->with(['mainBranch'])->paginate($pageSize, ['*'], 'page', $page);

    }

    public function getMerchantsByStatus(string $status): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        return $this->merchantModel->query()->where('status', $status)
            ->with(['mainBranch'])->paginate($pageSize, ['*'], 'page', $page);
    }

    private function updateMerchantStatus(int $id, string $status)
    {
        /** @var Merchant $merchant */
        $merchant = $this->getMerchant($id);
        if (is_null($merchant)) return;
        $merchant->status = $status;
        $merchant->save();
        $merchant->mainBranch()->update(['status' => $status]);
    }

    public function getMerchant(int $id): ?Model
    {
        return $this->merchantModel->query()->with(['mainBranch'])->find($id);
    }

    public function updateMerchant(User $user, array $payload, int $id)
    {
        $payload['last_updated_by'] = $user->id;
        try {
            $this->merchantModel->query()->find($id)->update($payload);
        } catch (Exception $e) {
        }
    }

    public function getMerchantBranches(User $user): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        /** @var Merchant $shop */
        $shop = $this->merchantModel->query()->find($user->merchant_id);
        return $shop->branches()->paginate($pageSize, ['*'], 'page', $page);
    }

    public function getMerchantUsers(User $user): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        /** @var Merchant $merchant */
        $merchant = $this->merchantModel->query()->find($user->merchant_id);
        return $merchant->users()->paginate($pageSize, ['*'], 'page', $page);
    }

    public function deleteMerchant(User $user)
    {
        $shop = $this->getMerchant($user->merchant_id);
        if ($shop != null) $shop->delete();
    }

    public function markAsActive(User $user)
    {
        $this->updateMerchantStatus($user->merchant_id, MerchantUtils::MERCHANT_STATUS_ACTIVE);
    }

    public function markAsBlocked(User $user)
    {
        $this->updateMerchantStatus($user->merchant_id, MerchantUtils::MERCHANT_STATUS_SUSPENDED);
    }

    public function markAsPending(User $user)
    {
        $this->updateMerchantStatus($user->merchant_id, MerchantUtils::MERCHANT_STATUS_PENDING);
    }

    public function deleteBranch(User $user, $branchId)
    {
        try {
            $this->branchModel->query()->where('merchant_id', $user->merchant_id)->where('id', $branchId)->delete();
        } catch (Exception $e) {

        }
    }

    public function changeBranchStatus(User $user, int $branchId, string $status)
    {
        /** @var Branch $branch */
        $branch = $this->branchModel->query()->where('merchant_id', $user->merchant_id)->where('id', $branchId)->first();
        if ($branch == null) return;

        $branch->status = $status;
        if ($branch->isDirty())
            $branch->save();
    }


    public function getMyMerchant(User $user): Model
    {
        return $this->merchantModel->query()->with(['mainBranch'])->find($user->merchant_id);
    }

}
