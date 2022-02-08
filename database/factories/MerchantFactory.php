<?php

namespace Database\Factories;

use App\Models\Merchant\Merchant;
use Illuminate\Database\Eloquent\Factories\Factory;

class MerchantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Merchant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'primary_email' => $this->faker->unique()->safeEmail(),
            'primary_phone' => $this->faker->unique()->phoneNumber(),
            'country_id' =>  $this->faker->randomElement([83,161]),
            'code' => $this->faker->unique()->randomNumber(6,true),
            'website' => $this->faker->url(),
            'status' => 'active',
            'primary_email_verified_at' => now()->toDateTimeString(),
            //'category_id' => MerchantCategory::factory(),
            'category_id' => $this->faker->randomElement([1,2]),
           // 'main_branch_id' => Branch::factory()
        ];
    }
}
