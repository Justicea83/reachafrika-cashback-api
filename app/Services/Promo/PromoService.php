<?php

namespace App\Services\Promo;

use App\Dtos\Promo\ScheduleDto;
use App\Models\Promo\PromoDay;
use App\Models\Promo\PromoFrequency;
use App\Models\Promo\PromoTime;
use App\Models\Promo\Schedule;
use App\Models\User;
use Illuminate\Support\Collection;

class PromoService implements IPromoService
{

    private PromoTime $promoTimeModel;
    private PromoDay $promoDayModel;
    private Schedule $scheduleModel;
    private PromoFrequency $promoFrequencyModel;

    function __construct(PromoTime $promoTimeModel, PromoDay $promoDayModel, Schedule $scheduleModel, PromoFrequency $promoFrequencyModel)
    {
        $this->promoDayModel = $promoDayModel;
        $this->promoTimeModel = $promoTimeModel;
        $this->scheduleModel = $scheduleModel;
        $this->promoFrequencyModel = $promoFrequencyModel;
    }

    public function getDays(): Collection
    {
        return $this->promoDayModel->query()->where('active', true)->select('id', 'description')->get();
    }

    public function getTime(): Collection
    {
        return $this->promoTimeModel->query()->where('active', true)->select('id', 'name_12', 'name_24')->get();
    }

    public function getFrequencies(): Collection
    {
        return $this->promoFrequencyModel->query()->where('active', true)->select('id', 'name')->get();
    }

    public function createSchedule(User $user, array $payload): ScheduleDto
    {
        $payload['merchant_id'] = $user->merchant_id;
        /** @var Schedule $createdSchedule */
        $createdSchedule = $this->scheduleModel->query()->create($payload);

        return ScheduleDto::map($createdSchedule);
    }

    public function getSchedules(User $user): Collection
    {
        return $this->scheduleModel->query()
            ->where('active', true)
            ->where('merchant_id', $user->merchant_id)
            ->get()
            ->map(function (Schedule $schedule) {
                return ScheduleDto::map($schedule);
            });
    }

    public function deleteSchedule(User $user, int $id)
    {
        $this->scheduleModel->query()->where('merchant_id', $user->merchant_id)->where('id', $id)->forceDelete();
    }

    public function toggleScheduleActive(User $user, int $id)
    {
        /** @var Schedule $schedule */
        $schedule = $this->scheduleModel->query()->where('merchant_id', $user->merchant_id)->where('id', $id)->first();

        $schedule->active = !$schedule->active;
        $schedule->save();
    }


}
