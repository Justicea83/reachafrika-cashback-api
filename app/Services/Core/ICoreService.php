<?php

namespace App\Services\Core;

use App\Http\Responses\Core\UserInfoByPhoneResponse;

interface ICoreService
{
    public function getUserInfoByPhone(string $phone) : UserInfoByPhoneResponse;
}
