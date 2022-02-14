<?php

namespace Database\Seeders;

use App\Models\Promo\PromoDay;
use App\Utils\Promo\PromoDayUtils;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PromoDaysTableSeeder extends Seeder
{
    private array $days = [
        PromoDayUtils::ONCE,
        PromoDayUtils::ALL_DAYS,
        PromoDayUtils::MONDAYS_TO_FRIDAYS,
        PromoDayUtils::SATURDAYS_TO_SUNDAYS,
        PromoDayUtils::MONDAYS,
        PromoDayUtils::TUESDAYS,
        PromoDayUtils::WEDNESDAYS,
        PromoDayUtils::THURSDAYS,
        PromoDayUtils::FRIDAYS,
        PromoDayUtils::SATURDAYS,
        PromoDayUtils::SUNDAYS
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (PromoDay::query()->count() <= 0)
            foreach ($this->days as $day) {
                PromoDay::query()->firstOrCreate([
                    'description' => $day,
                    'name' => PromoDayUtils::slugify($day)
                ]);
            }
    }
}
