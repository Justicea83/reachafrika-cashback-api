<?php

namespace App\Services\Settlements;

use App\Entities\Payments\Flutterwave\SubAccount;
use App\Entities\Payments\Paystack\Bank;
use App\Entities\Payments\Paystack\InitiateTransferRequest;
use App\Entities\Payments\Flutterwave\InitiateTransferRequest as InitiateFlutterwaveTransferRequest;
use App\Entities\Payments\Paystack\SubAccount as PaystackSubAccount;
use App\Entities\Payments\Paystack\TransferReceiptRequest;
use App\Facades\Payments\Flutterwave;
use App\Models\Finance\PaymentMode;
use App\Models\Merchant\Merchant;
use App\Models\Misc\Country;
use App\Models\Settlements\Settlement;
use App\Models\Settlements\SettlementBank;
use App\Models\Settlements\SettlementBankPurpose;
use App\Models\User;
use App\Services\Finance\Payment\Paystack\IPaystackService;
use App\Utils\Finance\PaymentMode\PaymentModeUtils;
use App\Utils\General\AppUtils;
use App\Utils\General\MiscUtils;
use App\Utils\MerchantUtils;
use App\Utils\Payments\Flutterwave\FlutterwaveUtility;
use App\Utils\Payments\Paystack\PaystackUtility;
use App\Utils\SettlementBankUtils;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;

class SettlementService implements ISettlementService
{
    private PaymentMode $paymentModeModel;
    private IPaystackService $paystackService;

    function __construct(PaymentMode $paymentModeModel, IPaystackService $paystackService)
    {
        $this->paymentModeModel = $paymentModeModel;
        $this->paystackService = $paystackService;
    }

    public function withdraw(User $user)
    {
        //TODO check if the amount is set to a certain limit
        //TODO make sure the admin sets that limit
        //TODO make sure the admin sets the split value as well
        // TODO: Implement withdraw() method.
    }

    public function withdrawOutstandingBalance(User $user, float $amount)
    {
        $merchant = $user->merchant;
        if ($merchant->normalAccount->outstanding_balance >= $amount) {
            /** @var SettlementBankPurpose $withdrawalPurpose */
            $withdrawalPurpose = $merchant->settlementBankPurposes()->where('purpose', SettlementBankUtils::SETTLEMENT_PURPOSE_WITHDRAWAL)->first();

            if (is_null($withdrawalPurpose)) throw new InvalidArgumentException("Settlement Bank with Withdrawal purpose not found, please add one");

            $settlementBank = $withdrawalPurpose->settlementBank;
            $paymentMode = $settlementBank->paymentMode;

            $merchantProfileId = MerchantUtils::getMerchantProfileId($user);

            /** @var object $shopApi */
            $shopApi = DB::connection('reachafrika_core')
                ->table('cashback_settlement_apis')
                ->where('cashback_settlement_apis.active', true)
                ->where('cashback_settlement_apis.profile_id', $merchantProfileId)
                ->join('shop_apis', 'cashback_settlement_apis.shop_api_id', 'shop_apis.id')
                ->select('shop_apis.id', 'shop_apis.name')
                ->first();

            $ref = strtolower(MiscUtils::getToken(32));

            if (!is_array($settlementBank->extra_info)) {
                $setup = json_decode($settlementBank->extra_info, true)['setup'];
            } else {
                $setup = $settlementBank->extra_info['setup'];
            }

            switch ($shopApi->name) {
                case FlutterwaveUtility::NAME:
                    $request = InitiateFlutterwaveTransferRequest::instance()
                        ->setBeneficiaryName($settlementBank->account_name)
                        ->setSender("ReachAfrika Pay")
                        ->setSenderCountry("NG")
                        ->setAmount($amount)
                        ->setNarration("Withdrawal from outstanding balance, ReachAfrika Pay")
                        ->setReference($ref)
                        ->setCurrency($merchant->country->currency)
                        ->setSenderMobileNumber("+2348170533018");

                    switch ($paymentMode->name) {
                        case PaymentModeUtils::PAYMENT_MODE_MOMO:
                            $accountNoWithoutPlus = str_replace('+', '', $settlementBank->account_no);
                            if ($setup['api'] == FlutterwaveUtility::NAME) {
                                $code = $setup['data']['code'];
                                $request->setAccountNumber($accountNoWithoutPlus)->setAccountBank($code);
                            } else {
                                $momoVendors = collect(Bank::instance()->fetchAll($merchant->country->name))->filter(fn($item) => $item['type'] == 'mobile_money');
                                $bank = $momoVendors->first(
                                    fn($item) => Str::contains(strtolower($setup['data']['name']), strtolower($item['name'])) ||
                                        Str::contains(strtolower($item['name']), strtolower($setup['data']['name']))
                                );
                                if ($bank == null) return;
                                $code = $bank['code'];
                            }
                            break;
                        case PaymentModeUtils::PAYMENT_MODE_BANK:
                            $request->setDestinationBranchCode("");
                            break;
                    }
                    dd($request);
                    break;
                case PaystackUtility::NAME:
                    switch ($paymentMode->name) {
                        case PaymentModeUtils::PAYMENT_MODE_MOMO:
                            $accountNo = str_replace('+233', '0', $settlementBank->account_no);
                            if ($setup['api'] == PaystackUtility::NAME) {
                                $code = $setup['data']['code'];
                            } else {
                                $momoVendors = collect(Bank::instance()->fetchAll($merchant->country->name))->filter(fn($item) => $item['type'] == 'mobile_money');
                                $bank = $momoVendors->first(
                                    fn($item) => Str::contains(strtolower($setup['data']['name']), strtolower($item['name'])) ||
                                        Str::contains(strtolower($item['name']), strtolower($setup['data']['name']))
                                );
                                if ($bank == null) return;
                                $code = $bank['code'];
                            }
                            $resolvedDetails = Bank::instance()->resolve($accountNo, $code);
                            $request = TransferReceiptRequest::instance()
                                ->setCurrency($merchant->country->currency)
                                ->setName($resolvedDetails['account_name'])
                                ->setType('mobile_money')
                                ->setAccountNumber($resolvedDetails['account_number'])
                                ->setBankCode($code);
                            $receiptResponse = $this->paystackService->createTransferReceipt($request);

                            if(!$receiptResponse->status) return;

                            $transferRequest = InitiateTransferRequest::instance()
                              //  ->setAmount($amount * 100)
                                ->setAmount( 1000)
                                ->setCurrency($merchant->country->currency)
                                ->setReference($ref)
                                ->setReason('Withdrawal from outstanding balance, ReachAfrika Pay')
                                ->setRecipient($receiptResponse->data['recipient_code']);

                            //dd($transferRequest);
                            $res = $this->paystackService->initiateTransfer($transferRequest);
                            dd($res);
                            break;
                        case PaymentModeUtils::PAYMENT_MODE_BANK:
                            break;
                    }
                    break;
            }

            dd($paymentMode->toArray());

            //create a settlement
            /** @var Settlement $settlement */
            $settlement = $merchant->settlements()->create([
                'country_id' => $merchant->country->id,
                'amount' => $amount,
                'reference' => $ref
            ]);
            //take out the amount since the request is valid
        }
        // TODO: Implement withdrawOutstandingBalance() method.
    }

    public function addMerchantSubAccounts(int $merchantId)
    {
        /** @var Merchant $merchant */
        $merchant = MerchantUtils::findById($merchantId);

        if (is_null($merchant)) return;

        $country = $merchant->country;

        /** @var PaymentMode $bankPaymentMode */
        $bankPaymentMode = $this->paymentModeModel->query()->where('name', PaymentModeUtils::PAYMENT_MODE_BANK)->first();
        if (is_null($bankPaymentMode)) return;

        $settlementBanks = $merchant->settlementBanks()->where('payment_mode_id', $bankPaymentMode->id)->where('verified', true)->get();

        if (is_null($country)) return;
        Log::info(get_class(), ['message' => 'adding subaccounts']);
        foreach ($settlementBanks as $settlementBank) {
            //add subaccount for paystack
            $this->paystackAddMerchantSubAccount($merchant, $country, $settlementBank);
            //add subaccount for flutterwave
            $this->flutterwaveAddMerchantSubAccount($merchant, $country, $settlementBank);
            //add for monnify later
        }
    }

    private function flutterwaveAddMerchantSubAccount(Merchant $merchant, Country $country, SettlementBank $settlementBank)
    {
        if (isset($settlementBank->extra_info['payment_info']) && isset($settlementBank->extra_info['payment_info'][FlutterwaveUtility::NAME])) return;

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

        $extraData = $settlementBank->extra_info;

        if (!is_array($extraData)) {
            $extraData = json_decode($extraData, true);
        }

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

        $settlementBank->extra_info = $extraData;
        $settlementBank->save();
    }

    private function paystackAddMerchantSubAccount(Merchant $merchant, Country $country, SettlementBank $settlementBank)
    {
        if (isset($settlementBank->extra_info['payment_info']) && isset($settlementBank->extra_info['payment_info'][PaystackUtility::NAME])) return;
        $countryName = $country->name;
        $accountNo = $settlementBank->account_no;
        $bankName = $settlementBank->bank_name;

        if (!app()->environment('production')) {
            $accountNo = '08100000000';
            $countryName = 'ghana';
            $bankName = 'Access Bank';
        }

        $data = Bank::instance()->fetchAll($countryName);

        $bank = collect($data)->first(function ($bank) use ($bankName) {
            return similar_text($bank['name'], $bankName) >= strlen(AppUtils::removeSpacesSpecialChar($bankName));
        });

        ['code' => $code] = $bank;

        $extraData = $settlementBank->extra_info;

        if (!is_array($extraData)) {
            $extraData = json_decode($extraData, true);
        }

        if (isset($extraData['payment_info']['paystack'])) return;

        $data = [
            "settlement_bank" => $code,
            "account_number" => $accountNo,
            "business_name" => $merchant->name,
            "domain" => 'test',
            "primary_contact_email" => $merchant->primary_email,
            "primary_contact_phone" => $merchant->primary_phone,
            "percentage_charge" => 11.5
        ];
        Log::info(get_class(), ['p' => $data]);
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

        $extraData['payment_info'][PaystackUtility::NAME] = $paystackInfo;

        $settlementBank->extra_info = $extraData;
        $settlementBank->save();
    }
}
