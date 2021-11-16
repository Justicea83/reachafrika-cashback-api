<?php

namespace App\Services\Settings\Cashback;

use App\Models\Settings\Cashback;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CashbackService implements ICashbackService
{
    private Cashback $cashbackModel;

    function __construct(Cashback $cashbackModel){
        $this->cashbackModel = $cashbackModel;
    }

    public function getCashbacks(User $user): LengthAwarePaginator
    {
        // TODO: Implement getCashbacks() method.
    }

    public function getDeletedCashbacks(User $user): Collection
    {
        // TODO: Implement getDeletedCashbacks() method.
    }

    public function getCashback(User $user, int $id): array
    {
        // TODO: Implement getCashback() method.
    }

    public function deleteCashback(User $user, int $id)
    {
        // TODO: Implement deleteCashback() method.
    }

    public function unDeleteCashback(User $user, int $id): array
    {
        // TODO: Implement unDeleteCashback() method.
    }

    public function createCashback(User $user, array $payload): array
    {
        // TODO: Implement createCashback() method.
    }
}
