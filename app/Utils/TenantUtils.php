<?php

namespace App\Utils;

class TenantUtils
{
    //roles
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'super';
    const ROLE_SUPPLIER = 'accountant';

    const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_SUPPLIER,
        self::ROLE_USER
    ];

    //permissions
}
