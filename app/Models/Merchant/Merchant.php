<?php

namespace App\Models\Merchant;

use App\Models\BaseModel;
use App\Models\Category\MerchantCategory;
use App\Models\Finance\Account;
use App\Models\Misc\Country;
use App\Models\Settlements\Settlement;
use App\Models\Settlements\SettlementBank;
use App\Models\Settlements\SettlementBankPurpose;
use App\Models\User;
use App\Utils\Finance\Merchant\Account\AccountUtils;
use App\Utils\SettlementBankUtils;
use ArrayAccess;
use Database\Factories\MerchantFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property mixed id
 * @property mixed|string status
 * @property mixed extra_data
 * @property array|ArrayAccess|mixed head_office_country_id
 * @property array|ArrayAccess|mixed head_office_state_id
 * @property array|ArrayAccess|mixed head_office_city
 * @property array|ArrayAccess|mixed head_office_street
 * @property array|ArrayAccess|mixed head_office_building
 * @property array|ArrayAccess|mixed head_office_address
 * @property array|ArrayAccess|mixed name
 * @property array|ArrayAccess|mixed category_id
 * @property array|ArrayAccess|mixed website
 * @property array|ArrayAccess|mixed about
 * @property false|mixed|string avatar
 * @property Country $country
 * @property Account $creditAccount
 * @property Account $normalAccount
 * @property Account $rewardAccount
 * @property Account $escrowAccount
 * @property SettlementBank $settlementBank
 * @property mixed $primary_email
 * @property mixed $primary_phone
 * @property Collection $settlementBanks
 */
class Merchant extends BaseModel
{
    protected $casts = [
        'extra_data' => 'array'
    ];

    public function mainBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'main_branch_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MerchantCategory::class, 'category_id');
    }

    protected static function newFactory(): MerchantFactory
    {
        return MerchantFactory::new();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @deprecated
     * */
    public function account(): HasOne
    {
        return $this->hasOne(Account::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function creditAccount(): HasOne
    {
        return $this->hasOne(Account::class)->where('type', AccountUtils::ACCOUNT_TYPE_CREDIT);
    }

    public function normalAccount(): HasOne
    {
        return $this->hasOne(Account::class)->where('type', AccountUtils::ACCOUNT_TYPE_NORMAL);
    }

    public function rewardAccount(): HasOne
    {
        return $this->hasOne(Account::class)->where('type', AccountUtils::ACCOUNT_TYPE_REWARD);
    }

    public function escrowAccount(): HasOne
    {
        return $this->hasOne(Account::class)->where('type', AccountUtils::ACCOUNT_TYPE_ESCROW);
    }

    public function settlementBank(): ?HasOne
    {
        /** @var SettlementBankPurpose $purpose */
        $purpose = SettlementBankPurpose::query()
            ->where('merchant_id', $this->id)
            ->where('purpose', SettlementBankUtils::SETTLEMENT_PURPOSE_COLLECTION)
            ->first();
        if ($purpose == null) return null;
        return $this->hasOne(SettlementBank::class)->where('id', $purpose->settlement_bank_id);
    }

    public function settlementBanks(): HasMany
    {
        return $this->hasMany(SettlementBank::class);
    }

    public function getExtraDataAttribute($value)
    {
        if (is_null($value)) return [];
        return json_decode($value, true);
    }

    public function setExtraDataAttribute($value)
    {
        $this->attributes['extra_data'] = json_encode($value);
    }

    public function settlements(): HasMany
    {
        return $this->hasMany(Settlement::class);
    }

    public function settlementBankPurposes(): HasMany
    {
        return $this->hasMany(SettlementBankPurpose::class);
    }
}
