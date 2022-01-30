<?php

namespace Database\Seeders;

use App\Models\Finance\PaymentMode;
use App\Utils\Finance\PaymentMode\PaymentModeUtils;
use Illuminate\Database\Seeder;

class PaymentModeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modes = [
            [
                'name' => PaymentModeUtils::PAYMENT_MODE_CASH,
                'display_name' => 'Cash/Money'
            ],
            [
                'name' => PaymentModeUtils::PAYMENT_MODE_CARD,
                'display_name' => 'Card'
            ],
            [
                'name' => PaymentModeUtils::PAYMENT_MODE_BANK,
                'display_name' => 'Bank'
            ],
            [
                'name' => PaymentModeUtils::PAYMENT_MODE_MOMO,
                'display_name' => 'Mobile Money'
            ],
            [
                'name' => PaymentModeUtils::PAYMENT_MODE_REACHAFRIKA,
                'display_name' => 'ReachAfrika Wallet'
            ],
            [
                'name' => PaymentModeUtils::PAYMENT_MODE_EXTERNAL_POS,
                'display_name' => 'External POS'
            ],
        ];

        foreach ($modes as $mode) {
            PaymentMode::query()->firstOrCreate($mode);
        }
    }
}
