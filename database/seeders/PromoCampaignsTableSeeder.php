<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PromoCampaignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Promo\Campaign\PromoCampaign::factory()->count(100)->create();
    }
}
