<?php


namespace App\Services\Auth;


use App\Models\User;

interface IAuthService
{
    public function mobileAppLogin(array $data) : array;
    public function getAuthUserProfile(User $user) : array;
    public function mobileLogout(User $user);
    public function logoutOfAllDevices(User $user);
    public function sendForgotPasswordEmail(User $user);
    public function sendForgotPasswordEmailWithEmail(string $email);
    public function resetPassword(array $payload) : bool;
}
