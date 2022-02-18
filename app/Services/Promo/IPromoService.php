<?php

namespace App\Services\Promo;

use App\Dtos\Promo\ScheduleDto;
use App\Models\User;
use Illuminate\Support\Collection;

interface IPromoService
{
    public function getDays(): Collection;

    public function getTime(): Collection;

    public function getFrequencies(): Collection;

    public function createSchedule(User $user, array $payload): ScheduleDto;

    public function getSchedules(User $user): Collection;

    public function deleteSchedule(User $user, int $id);

    public function toggleScheduleActive(User $user, int $id);

}
