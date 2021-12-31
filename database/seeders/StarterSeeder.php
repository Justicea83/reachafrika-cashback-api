<?php

namespace Database\Seeders;

use App\Models\Category\MerchantCategory;
use App\Models\Merchant\Branch;
use App\Models\Merchant\Merchant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class StarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(User::query()->count() <= 1){
            /** @var MerchantCategory $defaultCategory */
            $defaultCategory = MerchantCategory::query()->create([
                'name' => 'Shop'
            ]);
            /** @var Merchant $defaultMerchant */
            $defaultMerchant = Merchant::query()->create([
                'id' => 1,
                'name' => 'Frank Building Materials',
                'primary_email' => 'frank.building@yahoo.fr',
                'primary_phone' => '+233568769084',
                'country_id' => 3,
                'code' => 597892,
                'website' => 'https://franco.bl.dev',
                'primary_email_verified_at' => now()->toDateTimeString(),
                'category_id' => $defaultCategory->id
            ]);
            $defaultMerchant->account()->create();
            $defaultUser = User::query()->create([
               // 'merchant_id' => $defaultMerchant->id,
                'merchant_id' => 1,
                'first_name' => 'Frank',
                'last_name' => 'James',
                'email' => 'james@gmail.com',
                'phone' => '+233247545662',
                'password' => bcrypt('secret'),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]);
            $defaultRole = Role::query()->create([
                'name' => 'admin',
                'merchant_id' => $defaultMerchant->id,
                'display_name' => 'Administrator'
            ]);

            $defaultBranch = Branch::query()->create([
                'name' => 'Madina Branch',
                'opens_at' => '8:00',
                'closes_at' => '17:00',
                'merchant_id' => $defaultMerchant->id,
                'location' => 'Madina, Accra',
                'code' => 5245342345
            ]);
            //assign roles later
        }
    }
}
