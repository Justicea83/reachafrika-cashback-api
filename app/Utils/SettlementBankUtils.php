<?php

namespace App\Utils;

class SettlementBankUtils
{
    const SETTLEMENT_PURPOSE_COLLECTION = "collection";
    const SETTLEMENT_PURPOSE_WITHDRAWAL = "withdrawal";

    const SETTLEMENT_PURPOSES = [
        self::SETTLEMENT_PURPOSE_COLLECTION,
        self::SETTLEMENT_PURPOSE_WITHDRAWAL,
    ];
}
