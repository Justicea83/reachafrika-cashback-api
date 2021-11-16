<?php

namespace App\Services\Settings\BusinessInfo;

use App\Models\User;
use Illuminate\Http\UploadedFile;

interface IBusinessInfoService
{
    public function updateMerchantBusinessInfo(User $user,array $data,?UploadedFile $avatar = null);
}
