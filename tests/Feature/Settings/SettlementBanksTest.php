<?php

namespace Tests\Feature\Settings;


use App\Models\User;
use App\Traits\TestingMethods;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;
use Tests\CreatesApplication;
use Tests\TestCase;

class SettlementBanksTest extends TestCase
{
    use CreatesApplication,RefreshDatabase, TestingMethods;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate', ['-vvv' => true]);
        Artisan::call('passport:install', ['-vvv' => true]);
        Artisan::call('db:seed', ['-vvv' => true]);
    }

    function test_users_count()
    {
        $this->assertEquals(2, User::query()->count());
    }

    function test_create_settlement_bank_without_bank_name()
    {
        Passport::actingAs(
            $this->getDefaultUser(),
            ['*']
        );
        $response = $this->postJson('/api/v1/settings/settlement-banks',
            [
                // 'bank_name' => 'Access bank',
                'account_no' => '525324579345',
                'account_name' => 'Test Account'
            ]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    function test_create_settlement_bank()
    {
        $response = $this->actingAs($this->getDefaultUser(), 'api')->postJson('/api/v1/settings/settlement-banks',
            [
                'bank_name' => 'Access bank',
                'account_no' => '525324579345',
                'account_name' => 'Test Account'
            ]
        );
        $response->assertJson([
            'bank_name' => 'Access bank',
            'account_no' => '525324579345',
            'account_name' => 'Test Account'
        ])->assertStatus(Response::HTTP_CREATED);
    }

    function testGetSettlementBank()
    {
        $user = $this->getDefaultUser();
        $this->actingAs($user, 'api')->postJson('/api/v1/settings/settlement-banks',
            [
                'bank_name' => 'Access bank',
                'account_no' => '525324579345',
                'account_name' => 'Test Account'
            ]
        );
        $this->actingAs($user, 'api')->getJson('/api/v1/settings/settlement-banks')->assertJson(
            [
                'bank_name' => 'Access bank',
                'account_no' => '525324579345',
                'account_name' => 'Test Account'
            ]
        )->assertStatus(Response::HTTP_OK);
    }


}
