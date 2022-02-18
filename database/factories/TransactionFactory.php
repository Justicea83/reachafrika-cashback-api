<?php

namespace Database\Factories;

use App\Models\Finance\Transaction;
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
        $createdDate = random_int(now()->subMonths(5)->unix(), now()->unix());
        return [
            'amount' => random_int(100, 800),
            'transaction' => $faker->randomElement(['credit', 'debit']),
            'currency_symbol' => 'GH₵',
            'currency' => 'GHS',
            'balance_after' => 0,
            'balance_before' => 0,
            'given_discount' => 0,
            'tax_percentage' => 0,
            'transaction_type' => 'cashback',
            'payment_mode' => 'reachafrika_core_app',
            'status' => $faker->randomElement(['completed','pending','rejected']),
            'platform' => $faker->randomElement(['android','ios']),
            'reference' => (string) Str::uuid(),
            'group_reference' => (string) Str::uuid(),
            'service_charge' => 0,
            'user_phone' => $faker->phoneNumber(),
            'payment_mode_id' => random_int(1,6),
            'pos_id' => 1,
            'branch_id' => 12,
            'merchant_id' => 1,
            'created_by' => 1,
            'created_at' => $createdDate,
            'updated_at' => $createdDate,
        ];
    }
}
