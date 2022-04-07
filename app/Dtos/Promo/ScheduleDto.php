<?php

namespace App\Dtos\Promo;

use App\Dtos\BaseDto;
use App\Models\Promo\Schedule;

class ScheduleDto extends BaseDto
{

    public int $id;
    public array $from;
    public array $to;
    public string $day;

    /**
     * @param Schedule $model
     * @return void
     */
    public function mapFromModel($model): ScheduleDto
    {
        return self::instance()->setDay($model->day->description)
            ->setId($model->id)
            ->setFrom([
                'name_24' => $model->fromTime->name_24,
                'name_12' => $model->fromTime->name_12,
            ])->setTo([
                'name_24' => $model->toTime->name_24,
                'name_12' => $model->toTime->name_12,
            ]);
    }

    private static function instance(): ScheduleDto
    {
        return new ScheduleDto();
    }

    public static function map(Schedule $schedule): ScheduleDto
    {
        $instance = self::instance();
        return $instance->mapFromModel($schedule);
    }

    /**
     * @param int $id
     * @return ScheduleDto
     */
    public function setId(int $id): ScheduleDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param array $from
     * @return ScheduleDto
     */
    public function setFrom(array $from): ScheduleDto
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param array $to
     * @return ScheduleDto
     */
    public function setTo(array $to): ScheduleDto
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @param string $day
     * @return ScheduleDto
     */
    public function setDay(string $day): ScheduleDto
    {
        $this->day = $day;
        return $this;
    }
}
