<?php

namespace App\Utils;

class CollectionUtils
{
    const COLLECTION_TYPE_CATEGORY = 'categories';
    const COLLECTION_TYPE_COUNTRIES = 'countries';
    const COLLECTION_TYPE_COUNTRIES_STATES = 'countries.states';

    const COLLECTION_TYPES = [
        self::COLLECTION_TYPE_CATEGORY,
        self::COLLECTION_TYPE_COUNTRIES_STATES,
        self::COLLECTION_TYPE_COUNTRIES,
    ];
}
