<?php

namespace Database\Seeders;

use App\Models\Promo\Campaign\PromoCampaign;
use Illuminate\Database\Seeder;
use Throwable;

class PromoCampaignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            PromoCampaign::factory()->count(100)->create();
        }catch (Throwable $t){}
    }
}
