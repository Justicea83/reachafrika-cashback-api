<?php

namespace App\Utils;

class CollectionUtils
{
    const COLLECTION_TYPE_CATEGORY = 'categories';
    const COLLECTION_TYPE_COUNTRIES = 'countries';
    const COLLECTION_TYPE_BANKS = 'banks';
    const COLLECTION_TYPE_COUNTRIES_STATES = 'countries.states';

    const COLLECTION_TYPE_INTERESTS = "interests";
    const COLLECTION_TYPE_PROFESSIONS = "professions";
    const COLLECTION_TYPE_LANGUAGES = "languages";
    const COLLECTION_TYPE_GENDER = "gender";
    const COLLECTION_TYPE_EDUCATION = "education";
    const COLLECTION_TYPE_MARITAL_STATUS = "marital.status";
    const COLLECTION_TYPE_CAMPAIGNS = "campaign.types";
    const COLLECTION_TYPE_MERCHANT_SETTLEMENT_BANK_PURPOSES = 'merchant.settlement_banks.purposes';
    const COLLECTION_TYPE_MERCHANT_SETTLEMENT_BANK_TYPES = 'merchant.settlement_banks.types';
    const COLLECTION_TYPE_MERCHANT_SETTLEMENT_BANK_PAYMENT_MODES = 'merchant.settlement_banks.payment_modes';

    const COLLECTION_TYPES = [
        self::COLLECTION_TYPE_CATEGORY,
        self::COLLECTION_TYPE_COUNTRIES_STATES,
        self::COLLECTION_TYPE_COUNTRIES,

        self::COLLECTION_TYPE_INTERESTS,
        self::COLLECTION_TYPE_PROFESSIONS,
        self::COLLECTION_TYPE_LANGUAGES,
        self::COLLECTION_TYPE_CAMPAIGNS,
        self::COLLECTION_TYPE_GENDER,
        self::COLLECTION_TYPE_MARITAL_STATUS,
        self::COLLECTION_TYPE_EDUCATION,

        self::COLLECTION_TYPE_MERCHANT_SETTLEMENT_BANK_PURPOSES,
        self::COLLECTION_TYPE_MERCHANT_SETTLEMENT_BANK_PURPOSES,
        self::COLLECTION_TYPE_MERCHANT_SETTLEMENT_BANK_PAYMENT_MODES,
    ];

    const COLLECTION_TYPES_AUTH = [
        self::COLLECTION_TYPE_BANKS,
    ];
}
