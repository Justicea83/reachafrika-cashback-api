<?php

namespace Database\Seeders;

use App\Models\Finance\Account;
use App\Models\Finance\MerchantPaymentMode;
use App\Models\Merchant\Branch;
use App\Models\Merchant\Merchant;
use App\Utils\Finance\Merchant\Account\AccountUtils;
use Exception;
use Illuminate\Database\Seeder;

class MerchantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::factory(10)->create();

        foreach(Merchant::with('branches')->get() as $merchant){

            foreach (AccountUtils::ALL_ACCOUNT_TYPES as $accountType){
                try {
                    Account::factory()->for($merchant)->create([
                        'currency' => $merchant->country->currency,
                        'type' => $accountType
                    ]);
                }catch (Exception $e){}
            }

            MerchantPaymentMode::query()->create([
                'active' => 1,
                'disabled' => 1,
                'payment_mode_id' => 4,
                'merchant_id' => $merchant->id
            ]);

            if($merchant->main_branch_id == null){
                $merchant->main_branch_id = $merchant->branches->first()->id;
                $merchant->save();
            }
        }
    }
}
