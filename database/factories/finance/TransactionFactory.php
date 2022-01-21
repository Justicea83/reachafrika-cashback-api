<?php

namespace Database\Factories\Finance;

use App\Models\Merchant\Branch;
use App\Models\Merchant\Merchant;
use App\Models\Merchant\Pos;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $transactions = ['credit', 'debit'];
        $transactionTypes = ['Cashback Transaction', 'bonus transaction'];
        $amount = $this->faker->randomFloat(2, 1000, 10000);
        $status = ['pending', 'completed'];
        $platforms = ['ios', 'android', 'reachafrika-web'];
        $merchantId = Merchant::inRandomOrder()->get(['id'])->first()->id;
        $posId = Pos::inRandomOrder()->get(['id'])->first()->id;
        $branchId = Branch::inRandomOrder()->get(['id'])->first()->id;
        $usePhone = User::inRandomOrder()->get(['phone'])->first()->phone;

        return [
            'amount' => $amount,
            'transaction' => $transactions[rand(0, 1)],
            'transaction_type' => $transactions[rand(0, 1)],,
            'currency' => 'Naira',
            'currency_symbol' => 'NGN',
            'status' => $status[rand(0.1)],
            'reference' => str_replace('-', '', $this->faker->uuid()),
            'group_reference' => str_replace('-', '', $this->faker->uuid()),
            'user_phone' => $usePhone,
            'platform' => $platforms[rand(0, 2)],
            'pos_id' => $posId,
            'branch_id' => $branchId,
            'merchant_id' => $merchantId,
        ];
    }
}
