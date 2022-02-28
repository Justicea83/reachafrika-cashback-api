<?php

namespace Database\Factories;

use App\Models\Finance\PaymentMode;
use App\Models\Finance\Transaction;
use App\Models\Merchant\Branch;
use App\Models\Merchant\Merchant;
use App\Models\Merchant\Pos;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;


    public function definition(): array
    {
        $faker = $this->withFaker();

        $merchantId = Merchant::inRandomOrder()->get(['id'])->first()->id;
        $posId = Pos::inRandomOrder()->get(['id'])->first()->id;
        $branchId = Branch::inRandomOrder()->get(['id'])->first()->id;
        $usePhone = User::inRandomOrder()->get(['phone'])->first()->phone;
        $createdDate = random_int(now()->subMonths(5)->unix(), now()->unix());
        $paymentModeId = PaymentMode::inRandomOrder()->get(['id'])->first()->id;

        return [
            'amount' => $faker->randomFloat(2, 1000, 10000),
            'transaction' => $faker->randomElement(['credit', 'debit']),
            'currency_symbol' => 'GH₵',
            'currency' => 'GHS',
            'balance_after' => 0,
            'balance_before' => 0,
            'given_discount' => $faker->randomFloat(2, 10, 100),
            'tax_percentage' => $faker->randomFloat(1, 1, 20),
            'transaction_type' => $faker->randomElement(['Cashback', 'bonus transaction']),
            'payment_mode' => 'reachafrika_core_app',
            'status' => $faker->randomElement(['completed','pending','rejected']),
            'platform' => $faker->randomElement(['android','ios']),
            'reference' => (string) Str::uuid(),
            'group_reference' => (string) Str::uuid(),
            'service_charge' => $faker->randomFloat(1, 10, 100),
            'user_phone' => $usePhone ?? $faker->phoneNumber(),
            'payment_mode_id' => $paymentModeId ?? random_int(1, 6),
            'pos_id' => $posId ?? 1,
            'branch_id' => $branchId ?? 12,
            'merchant_id' => $merchantId ?? 1,
            'created_by' => 1,
            'created_at' => $createdDate,
            'updated_at' => $createdDate,
        ];
    }
}
