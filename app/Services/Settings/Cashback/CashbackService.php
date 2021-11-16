<?php

namespace App\Services\Settings\Cashback;

use App\Models\Merchant\Merchant;
use App\Models\Settings\Cashback;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class CashbackService implements ICashbackService
{
    private Cashback $cashbackModel;

    function __construct(Cashback $cashbackModel)
    {
        $this->cashbackModel = $cashbackModel;
    }

    public function getCashbacks(User $user): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        return $this->cashbackModel->query()
            ->where('merchant_id', $user->merchant_id)
            ->paginate($pageSize, ['*'], 'page', $page);
    }

    public function getDeletedCashbacks(User $user): Collection
    {
        return $this->cashbackModel->query()->onlyTrashed()->get();
    }

    public function getCashback(User $user, int $id): ?Model
    {
        return $this->cashbackModel->query()->where('merchant_id', $user->merchant_id)->find($id);
    }


    public function deleteCashback(User $user, int $id)
    {
        $cashback = $this->getCashback($user, $id);
        if($cashback == null) return;
        try {
            $cashback->delete();
        } catch (Exception $e) {

        }
    }

    public function unDeleteCashback(User $user, int $id): Model
    {
        $this->cashbackModel->query()->withTrashed()->find($id)->restore();
        return $this->getCashback($user, $id);
    }

    public function createCashback(User $user, array $payload): Model
    {
        ['is_fixed' => $isFixed, 'branch_id' => $branchId] = $payload;
        $cashback = new Cashback;
        $cashback->is_fixed = $isFixed;
        $cashback->merchant_id = $user->merchant_id;
        $cashback->branch_id = $branchId;
        if ($isFixed) {
            ['fixed_bonus' => $fixedBonus] = $payload;
            $cashback->fixed_bonus = $fixedBonus;
        } else {
            ['bonus_percentage' => $percentage, 'end' => $end, 'start' => $start] = $payload;
            $cashback->bonus_percentage = $percentage;
            $cashback->end = $end;
            $cashback->start = $start;
        }

        $cashback->save();

        return $this->getCashback($user, $cashback->id);
    }

    public function updateCashback(User $user, int $id, array $payload)
    {
        /** @var Cashback $cashback */
        $cashback = $this->getCashback($user, $id);

        if ($cashback == null) throw new InvalidArgumentException("cashback not found");

        ['is_fixed' => $isFixed, 'branch_id' => $branchId] = $payload;

        $cashback->is_fixed = $isFixed;
        $cashback->branch_id = $branchId;

        if ($isFixed) {
            ['fixed_bonus' => $fixedBonus] = $payload;
            $cashback->fixed_bonus = $fixedBonus;
            $cashback->end = null;
            $cashback->start = null;
            $cashback->bonus_percentage = null;
        } else {
            ['bonus_percentage' => $percentage, 'end' => $end, 'start' => $start] = $payload;
            $cashback->fixed_bonus = null;
            $cashback->bonus_percentage = $percentage;
            $cashback->end = $end;
            $cashback->start = $start;
        }

        if ($cashback->isDirty()) {
            $cashback->save();
        }
    }
}
