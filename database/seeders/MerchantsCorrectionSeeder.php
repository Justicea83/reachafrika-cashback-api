<?php

namespace Database\Seeders;

use App\Models\Merchant\Merchant;
use App\Utils\Finance\Merchant\Account\AccountUtils;
use Illuminate\Database\Seeder;

class MerchantsCorrectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Merchant $merchant */
        foreach (Merchant::query()->get() as $merchant) {
            if ($merchant->accounts()->count() > 0) continue;
            foreach (AccountUtils::ALL_ACCOUNT_TYPES as $accountType) {
                $merchant->accounts()->create([
                    'currency' => $merchant->country->currency,
                    'type' => $accountType
                ]);
            }
        }
    }
}
