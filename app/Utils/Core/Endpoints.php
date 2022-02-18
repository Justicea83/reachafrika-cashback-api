<?php

namespace App\Utils\Core;

class Endpoints
{
    const V2 = '/api/v2';
    const CASHBACK_ENDPOINTS = self::V2 . '/cashback';
    const PAY_ENDPOINTS = self::CASHBACK_ENDPOINTS . '/pay';
    const TOKEN_ENDPOINT = 'oauth/token';
    const POS_APPROVAL_ACTION_CALL_ENDPOINT = self::PAY_ENDPOINTS . '/pos-approval-action-call';
    const LISTS_ENDPOINT = self::V2 . '/lists/';

    public static function getEndpointForAction(string $endpoint): string
    {
        return sprintf("%s%s", config('core.app.api.url'), $endpoint);
    }
}
