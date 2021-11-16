<?php

namespace Tests\Feature\Settings;

use App\Models\Merchant\Branch;
use App\Traits\TestingMethods;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
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

    public function test_create_cashback_with_fixed_bonus_false_and_invalid_percentage_value_greater_than_100()
    {
        Passport::actingAs(
            $this->getDefaultUser(),
            ['*']
        );
        $response = $this->postJson('/api/v1/settings/cashbacks', [
            'is_fixed' => false,
            'bonus_percentage' => 120
        ]);

        //dd($response->json());
        $response->assertStatus(422);

        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "branch_id" => [
                    "The branch id field is required."
                ],
                "bonus_percentage" => [
                    "The bonus percentage must not be greater than 100."
                ],
                "start" => [
                    "The start field is required."
                ],
                "end" => [
                    "The end field is required."
                ],
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
            'is_fixed' => false,
            'bonus_percentage' => -23
        ]);

        //dd($response->json());
        $response->assertStatus(422);

        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "branch_id" => [
                    "The branch id field is required."
                ],
                "bonus_percentage" => [
                    "The bonus percentage must be at least 1."
                ],
                "start" => [
                    "The start field is required."
                ],
                "end" => [
                    "The end field is required."
                ],
            ]
        ]);
    }

    public function test_create_cashback_with_valid_data_and_fixed_of_false()
    {
        Passport::actingAs(
            $this->getDefaultUser(),
            ['*']
        );

        /** @var Branch $branch */
        $branch = Branch::factory()->create();

        $response = $this->postJson('/api/v1/settings/cashbacks', [
            'is_fixed' => false,
            'bonus_percentage' => 23,
            'start' => 100,
            'end' => 5000,
            'branch_id' => $branch->id
        ]);


        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment([
            "start" => "100",
            "end" => "5000",
            "bonus_percentage" => "23",
            "is_fixed" => "0",
            "fixed_bonus" => null,
            "status" => "active",
        ]);
    }

    public function test_create_cashback_with_valid_data_and_fixed_of_true()
    {
        Passport::actingAs(
            $this->getDefaultUser(),
            ['*']
        );

        /** @var Branch $branch */
        $branch = Branch::factory()->create();

        $response = $this->postJson('/api/v1/settings/cashbacks', [
            'is_fixed' => true,
            'fixed_bonus' => 4000,
            'branch_id' => $branch->id
        ]);


        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment([
            "start" => null,
            "end" => null,
            "bonus_percentage" => null,
            "is_fixed" => "1",
            "fixed_bonus" => "4000",
            "status" => "active",
        ]);
    }

    public function test_create_cashback_with_valid_data_and_fixed_of_true_which_already_exists()
    {
        Passport::actingAs(
            $this->getDefaultUser(),
            ['*']
        );

        /** @var Branch $branch */
        $branch = Branch::factory()->create();

        $this->postJson('/api/v1/settings/cashbacks', [
            'is_fixed' => true,
            'fixed_bonus' => 4000,
            'branch_id' => $branch->id
        ]);

        $response = $this->postJson('/api/v1/settings/cashbacks', [
            'is_fixed' => true,
            'fixed_bonus' => 4000,
            'branch_id' => $branch->id
        ]);


        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "cashback" => ["a cashback with this bonus already exists"],
            ]
        ]);
    }

    public function test_create_cashback_with_valid_data_and_fixed_of_false_which_already_exists()
    {
        Passport::actingAs(
            $this->getDefaultUser(),
            ['*']
        );

        /** @var Branch $branch */
        $branch = Branch::factory()->create();

        $this->postJson('/api/v1/settings/cashbacks', [
            'is_fixed' => false,
            'bonus_percentage' => 23,
            'start' => 100,
            'end' => 5000,
            'branch_id' => $branch->id
        ]);

        $response = $this->postJson('/api/v1/settings/cashbacks', [
            'is_fixed' => false,
            'bonus_percentage' => 23,
            'start' => 100,
            'end' => 5000,
            'branch_id' => $branch->id
        ]);


        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "cashback" => ["invalid 'start' and 'end' range"],
            ]
        ]);
    }

    public function test_update_cashback_with_valid_data_which_already_exists()
    {
        Passport::actingAs(
            $this->getDefaultUser(),
            ['*']
        );

        /** @var Branch $branch */
        $branch = Branch::factory()->create();

        $this->postJson('/api/v1/settings/cashbacks', [
            'is_fixed' => false,
            'bonus_percentage' => 23,
            'start' => 100,
            'end' => 500,
            'branch_id' => $branch->id
        ]);

        $cashback2 = $this->postJson('/api/v1/settings/cashbacks', [
            'is_fixed' => false,
            'bonus_percentage' => 30,
            'start' => 501,
            'end' => 1000,
            'branch_id' => $branch->id
        ]);

        $response = $this->putJson("/api/v1/settings/cashbacks/{$cashback2['id']}", [
            'is_fixed' => false,
            'bonus_percentage' => 23,
            'start' => 101,
            'end' => 400,
            'branch_id' => $branch->id
        ]);


        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "cashback" => ["invalid 'start' and 'end' range"],
            ]
        ]);
    }
}
