<?php

namespace App\Utils;

class PosApprovalUtils
{
    const ACTION_APPROVE = 'approve';
    const ACTION_DENY = 'deny';

    const ACTIONS = [
        self::ACTION_APPROVE,
        self::ACTION_DENY
    ];
}
