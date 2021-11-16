<?php

namespace Tests\Feature\Settings;

use App\Traits\TestingMethods;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\CreatesApplication;
use Tests\TestCase;

class CashbackTest extends TestCase
{
    use CreatesApplication, RefreshDatabase, TestingMethods;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate', ['-vvv' => true]);
        Artisan::call('passport:install', ['-vvv' => true]);
        Artisan::call('db:seed', ['-vvv' => true]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_cashback_with_fixed_bonus_false()
    {
        Passport::actingAs(
            $this->getDefaultUser(),
            ['*']
        );
        $response = $this->postJson('/api/v1/settings/cashbacks', [
            'is_fixed' => false
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "branch_id" => [
                    "The branch id field is required."
                ],
                "start" => [
                    "The start field is required."
                ],
                "end" => [
                    "The end field is required."
                ],
                "bonus_percentage" => [
                    "The bonus percentage field is required."
                ]
            ]
        ]);
    }

    public function test_create_cashback_with_fixed_bonus_true()
    {
        Passport::actingAs(
            $this->getDefaultUser(),
            ['*']
        );
        $response = $this->postJson('/api/v1/settings/cashbacks', [
            'is_fixed' => true
        ]);

        //dd($response->json());
        $response->assertStatus(422);

        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "branch_id" => [
                    "The branch id field is required."
                ],
                "fixed_bonus" => [
                    "The fixed bonus field is required."
                ]
            ]
        ]);
    }

    public function test_create_cashback_with_fixed_bonus_true_and_invalid_percentage_value_greater_than_100()
    {
        Passport::actingAs(
            $this->getDefaultUser(),
            ['*']
        );
        $response = $this->postJson('/api/v1/settings/cashbacks', [
            'is_fixed' => true,
            'fixed_bonus' => 120
        ]);

        //dd($response->json());
        $response->assertStatus(422);

        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "branch_id" => [
                    "The branch id field is required."
                ],
                "fixed_bonus" => [
                    "The fixed bonus must not be greater than 100."
                ]
            ]
        ]);
    }

    public function test_create_cashback_with_fixed_bonus_true_and_invalid_percentage_value_less_than_0()
    {
        Passport::actingAs(
            $this->getDefaultUser(),
            ['*']
        );
        $response = $this->postJson('/api/v1/settings/cashbacks', [
            'is_fixed' => true,
            'fixed_bonus' => -23
        ]);

        //dd($response->json());
        $response->assertStatus(422);

        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "branch_id" => [
                    "The branch id field is required."
                ],
                "fixed_bonus" => [
                    "The fixed bonus must be at least 1."
                ]
            ]
        ]);
    }
}
