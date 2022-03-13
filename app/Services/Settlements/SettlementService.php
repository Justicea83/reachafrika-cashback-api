<?php

namespace App\Services\Settlements;

use App\Entities\Payments\Flutterwave\SubAccount;
use App\Entities\Payments\Paystack\Bank;
use App\Entities\Payments\Paystack\SubAccount as PaystackSubAccount;
use App\Facades\Payments\Flutterwave;
use App\Models\Merchant\Merchant;
use App\Models\Misc\Country;
use App\Models\SettlementBank;
use App\Utils\Finance\Merchant\Account\AccountUtils;
use App\Utils\General\AppUtils;
use App\Utils\MerchantUtils;
use App\Utils\Payments\Flutterwave\FlutterwaveUtility;
use App\Utils\Payments\Paystack\PaystackUtility;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SettlementService implements ISettlementService
{

    private Merchant $merchantModel;

    function __construct(Merchant $merchantModel)
    {
        $this->merchantModel = $merchantModel;
    }

    public function settleMerchants()
    {
        $this->merchantModel->query()
            ->join('accounts', 'accounts.merchant_id', 'merchants.id')
            ->where('accounts.type', AccountUtils::ACCOUNT_TYPE_NORMAL)
            ->where('accounts.balance', '>', 0)
            // ->whereRaw("accounts.last_settlement_at IS NULL OR accounts.last_settlement_at = ?", [now()->subHours(23)->toDateString()])
            ->select(['merchants.id', 'merchants.primary_email as email', 'merchants.country_id'])
            ->chunkById(50, function (Collection $merchants) {
                dd($merchants->toArray());
            }, 'merchants.id');
    }

    public function reverseTransactionForMerchant()
    {
        // TODO: Implement reverseTransactionForMerchant() method.
    }


    public function addMerchantSubAccounts(int $merchantId)
    {
        /** @var Merchant $merchant */
        $merchant = MerchantUtils::findById($merchantId);

        if (is_null($merchant)) return;

        $country = $merchant->country;
        $settlementBank = $merchant->settlementBank;

        if (is_null($country) || is_null($settlementBank)) return;

        //add subaccount for flutterwave
        $this->flutterwaveAddMerchantSubAccount($merchant, $country, $settlementBank);
        //add subaccount for paystack
        $this->paystackAddMerchantSubAccount($merchant, $country, $settlementBank);
        // TODO: Implement addMerchantSubAccounts() method.
    }

    private function flutterwaveAddMerchantSubAccount(Merchant $merchant, Country $country, SettlementBank $settlementBank)
    {
        //TODO check if the country is a supported country
        $key = Str::of(sprintf('countries_%s', $country->currency))->prepend(FlutterwaveUtility::CACHE_PREFIX);

        if (Cache::has($key)) {
            $data = Cache::get($key);
        } else {
            $data = Flutterwave::banks()->{strtolower($country->name)}();
            if ($data['status'] != FlutterwaveUtility::SUCCESS) return;
            //store the data in the cache
            Cache::put($key, $data, now()->addHours(24));
        }


        $bank = collect($data['data'])->first(function ($bank) use ($settlementBank) {
            return similar_text($bank['name'], $settlementBank->bank_name) >= strlen(AppUtils::removeSpacesSpecialChar($settlementBank->bank_name));
        });

        ['code' => $code] = $bank;

        $accountNo = $settlementBank->account_no;
        $countryCode = $country->iso2;

        $extraData = $merchant->extra_data;

        if (isset($extraData['payment_info']['flutterwave'])) return;

        if (!app()->environment('production')) {
            //https://developer.flutterwave.com/docs/integration-guides/testing-helpers/
            $code = '044';
            $accountNo = '0690000032';
            $countryCode = 'NG';
        }

        $data = [
            "account_bank" => $code,
            "account_number" => $accountNo,
            "business_name" => $merchant->name,
            "business_email" => $merchant->primary_email,
            "business_contact" => $merchant->primary_phone,
            "business_contact_mobile" => $merchant->primary_phone,
            "business_mobile" => $merchant->primary_phone,
            "country" => $countryCode,
            "split_type" => "percentage",
            "split_value" => 0.05
        ];


        $subaccount = (new SubAccount())->create($data);

        if ($subaccount == null) return;

        $flutterwaveInfo = [
            'sub_account_id' => $subaccount->subaccount_id,
            'id' => $subaccount->id,
            'bank_name' => $subaccount->bank_name,
        ];

        $extraData['payment_info']['flutterwave'] = $flutterwaveInfo;

        $merchant->extra_data = $extraData;
        $merchant->save();
    }

    private function paystackAddMerchantSubAccount(Merchant $merchant, Country $country, SettlementBank $settlementBank)
    {
        //TODO check if the country is a supported country

        $countryName = $country->name;
        $accountNo = $settlementBank->account_no;
        $bankName = $settlementBank->bank_name;

        if (!app()->environment('production')) {
            $accountNo = '08100000000';
            $countryName = 'ghana';
            $bankName = 'Access Bank';
        }

        $key = Str::of(sprintf('countries_%s', $countryName))->prepend(PaystackUtility::CACHE_PREFIX);

        if (Cache::has($key)) {
            $data = Cache::get($key);
        } else {
            $data = Bank::instance()->fetchAll($countryName);

            if (count($data) <= 0) return;
            //store the data in the cache
            Cache::put($key, $data, now()->addHours(24));
        }


        $bank = collect($data)->first(function ($bank) use ($bankName) {
            return similar_text($bank['name'], $bankName) >= strlen(AppUtils::removeSpacesSpecialChar($bankName));
        });


        ['code' => $code] = $bank;

        $extraData = $merchant->extra_data;

        if (isset($extraData['payment_info']['paystack'])) return;

        $data = [
            "settlement_bank" => $code,
            "account_number" => $accountNo,
            "business_name" => $merchant->name,
            "domain" => 'test',
            "primary_contact_email" => $merchant->primary_email,
            "primary_contact_phone" => $merchant->primary_phone,
            "percentage_charge" => 0.05
        ];

        if (!app()->environment('production')) {
            $data['domain'] = 'test';
        }
        /** @var PaystackSubAccount $subaccount */
        $subaccount = PaystackSubAccount::instance()->create($data);

        if ($subaccount == null) return;

        $paystackInfo = [
            'sub_account_id' => $subaccount->subaccount_code,
            'id' => $subaccount->id,
            'bank_name' => $subaccount->settlement_bank
        ];

        $extraData['payment_info']['paystack'] = $paystackInfo;

        $merchant->extra_data = $extraData;
        $merchant->save();
    }
}
