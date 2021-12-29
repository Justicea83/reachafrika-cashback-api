<?php

namespace App\Http\Responses\Core;

class UserInfoByPhoneResponse
{
    function __construct(string $phone, bool $userFound, $info = null){
        $this->phone = $phone;
        $this->userFound = $userFound;
        $this->info = $info;
    }
    public string $phone;
    public bool $userFound;
    public array $info;
}
