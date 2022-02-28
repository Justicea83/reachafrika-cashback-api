<?php

namespace Database\Factories;

use App\Models\Merchant\Merchant;
use App\Models\Promo\Campaign\PromoCampaign;
use App\Models\Promo\PromoFrequency;
use App\Models\User as ModelsUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromoCampaignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PromoCampaign::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->withFaker();
        return [
            'start' => Carbon::now()->addWeek(rand(1, 3))->unix(),
            'end'   => Carbon::now()->now()->addWeek(rand(4, 7))->unix(),
            'type'  => $faker->randomElement(['flyer', 'video']),
            'title' => $faker->title(),
            'merchant_id'   => Merchant::inRandomOrder()->get(['id'])->first()->id,
            'budget'    => $faker->randomFloat(2, 100, 1000),
            'impressions' => rand(0, 5),
            'impressions_track' => 0,
            'media' => 'gL4dGa6E0G9YRH1zz1gdxq71zy2eai0PwMJKGLlF.mp4',
            'thumbnail' => '',
            'promo_frequency_id'    => PromoFrequency::inRandomOrder()->get(['id'])->first()->id,
            'created_at'    => '1645195518',
            'updated_at'    => '1645195518',
        ];
    }
}
