<?php

namespace App\Services\Notifications\Fcm;

use App\Models\User;

interface IFcmNotificationService
{
    public function register(User $user, string $token, string $device);
    public function addTokenToDeviceGroup(User $user, string $token);
    public function pruneNotificationTokens();
}
