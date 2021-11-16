<?php

namespace App\Traits;

use App\Models\User;

trait TestingMethods
{
    public function getDefaultUser(): User
    {
        /** @var User $user */
        $user = User::query()->where('email', 'james@gmail.com')->first();
        return $user;
    }
}
