<?php

namespace Database\Seeders;

use App\Models\Promo\PromoFrequency;
use Illuminate\Database\Seeder;

class PromoFrequencyTableSeeder extends Seeder
{
    private array $data = [
        [
            'name' => 'Once per 10 min',
            'count' => 1,
            'interval' => 10
        ],
        [
            'name' => 'Once per 30 min',
            'count' => 1,
            'interval' => 30
        ],
        [
            'name' => 'Once per hour',
            'count' => 1,
            'interval' => 60
        ],
    ];

    public function run()
    {
        if (PromoFrequency::query()->count() <= 0)
            foreach ($this->data as $item) {
                PromoFrequency::query()->firstOrCreate($item);
            }
    }
}
