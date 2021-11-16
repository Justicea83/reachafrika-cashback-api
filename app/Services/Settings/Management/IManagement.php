<?php

namespace App\Services\Settings\Management;

use App\Models\User;
use Illuminate\Support\Collection;

interface IManagement
{
    public function getRoles(User $user) : Collection;
    public function addRoles(User $user, array $payload);
    public function removeRoles(User $user, array $payload);
    public function getPermissions(User $user) : Collection;
}
