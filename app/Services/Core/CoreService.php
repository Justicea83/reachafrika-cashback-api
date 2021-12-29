<?php

namespace App\Services\Core;

use App\Http\Responses\Core\UserInfoByPhoneResponse;
use App\Models\Core\User;

class CoreService implements ICoreService
{
    private User $coreUserModel;
    function __construct(User $coreUserModel){
        $this->coreUserModel = $coreUserModel;
    }

    public function getUserInfoByPhone(string $phone): UserInfoByPhoneResponse
    {
        /** @var User $userDetails */
        $userDetails = $this->coreUserModel->query()->where('phone',$phone)->first();
        if($userDetails == null) return new UserInfoByPhoneResponse($phone,false);

        return new UserInfoByPhoneResponse($phone,true,[
            'first_name' => $userDetails->firstname,
            'last_name' => $userDetails->lastname,
            'email' => $userDetails->email,
            'id' => $userDetails->id,
            'avatar' => $userDetails->image
        ]);
    }
}
