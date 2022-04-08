<?php

namespace App\Utils;

class SettlementBankUtils
{
    const SETTLEMENT_PURPOSE_COLLECTION = "collection";
    const SETTLEMENT_PURPOSE_WITHDRAWAL = "withdrawal";
    const SETTLEMENT_TYPE_MOMO = 'momo';
    const SETTLEMENT_TYPE_BANK = 'bank';


    const SETTLEMENT_PURPOSES = [
        self::SETTLEMENT_PURPOSE_COLLECTION,
        self::SETTLEMENT_PURPOSE_WITHDRAWAL,
    ];

    const SETTLEMENT_TYPES = [
        self::SETTLEMENT_TYPE_BANK,
        self::SETTLEMENT_TYPE_MOMO,
    ];
}
