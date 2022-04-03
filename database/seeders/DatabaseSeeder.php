<?php

namespace Database\Seeders;

use App\Models\Promo\PromoFrequency;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(MerchantsCorrectionSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(MerchantCategoryTableSeeder::class);
        // $this->call(StarterSeeder::class);
        $this->call(PaymentModeTableSeeder::class);
        // $this->call(CityTableSeeder::class);
      //  $this->call(MerchantsTableSeeder::class);
        //$this->call(TransactionsTableSeeder::class);
        $this->call(PromoDaysTableSeeder::class);
        $this->call(PromoTimesTableSeeder::class);
        $this->call(PromoFrequencyTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PromoCampaignsTableSeeder::class);
    }
}
