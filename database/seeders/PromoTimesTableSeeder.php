<?php

namespace Database\Seeders;

use App\Models\Promo\PromoTime;
use App\Utils\Promo\PromoTimeUtils;
use Illuminate\Database\Seeder;

class PromoTimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (PromoTime::query()->count() <= 0)
            for ($i = 0; $i <= 23; $i++) {
                $hour24 = PromoTimeUtils::get24thHourName($i);
                $hour12 = PromoTimeUtils::get12thHourName($i);
                for ($j = 0; $j <= 3; $j++) {
                    PromoTime::query()->firstOrCreate([
                        'name_24' => sprintf("%s:%s", $hour24, PromoTimeUtils::getMinuteInterval($i, $j, false)),
                        'name_12' => sprintf("%s:%s", $hour12, PromoTimeUtils::getMinuteInterval($i, $j, true)),
                    ]);
                }
            }
    }
}
