<?php

namespace Database\Factories;

use App\Models\Category\MerchantCategory;
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
            'primary_phone' =>$this->faker->unique()->phoneNumber(),
            'country_id' => 3,
            'code' => $this->faker->randomDigitNotNull(),
            'website' => $this->faker->url,
            'primary_email_verified_at' => now()->toDateTimeString(),
            'category_id' => MerchantCategory::factory()->create()
        ];
    }
}
