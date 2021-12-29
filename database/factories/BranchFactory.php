<?php

namespace Database\Factories;

use App\Models\Merchant\Branch;
use App\Models\Merchant\Merchant;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'opens_at' => '8:00',
            'closes_at' => '17:00',
            'lat' => $this->faker->latitude(),
            'code' => $this->faker->unique()->randomNumber(8,true),
            'lng' => $this->faker->longitude(),
            'merchant_id' => Merchant::factory(),
            'description' => $this->faker->realText(),
            'location' => $this->faker->city
        ];
    }
}
