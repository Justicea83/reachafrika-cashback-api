<?php

namespace App\Services\Settings\Cashback;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ICashbackService
{
    public function getCashbacks(User $user): LengthAwarePaginator;

    public function getDeletedCashbacks(User $user): Collection;

    public function getCashback(User $user,int $id): array;

    public function deleteCashback(User $user,int $id);

    public function unDeleteCashback(User $user, int $id): array;

    public function createCashback(User $user,array $payload): array;
}
