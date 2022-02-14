<?php

namespace App\Utils;

class CollectionUtils
{
    const COLLECTION_TYPE_CATEGORY = 'categories';
    const COLLECTION_TYPE_COUNTRIES = 'countries';
    const COLLECTION_TYPE_COUNTRIES_STATES = 'countries.states';

    const COLLECTION_TYPE_INTERESTS = "interests";
    const COLLECTION_TYPE_PROFESSIONS = "professions";
    const COLLECTION_TYPE_LANGUAGES = "languages";
    const COLLECTION_TYPE_GENDER = "gender";
    const COLLECTION_TYPE_MARITAL_STATUS = "marital.status";
    const COLLECTION_TYPE_CAMPAIGNS = "campaign.types";

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
    ];
}
