<?php


namespace App\Services\Auth;


use App\Mail\Auth\ForgotPasswordMail;
use App\Mail\Auth\NewUserPasswordChange;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AuthService implements IAuthService
{
    private User $userModel;

    function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function mobileAppLogin(array $data): array
    {
        /** @var User $user */
        $user = $this->userModel->query()->where('email', $data['email'])->first();

        if (!$user) return [];

        return [
            'token' => $user->createToken($data['device_name'])->plainTextToken
        ];
    }

    public function mobileLogout(User $user)
    {
        $user->currentAccessToken()->delete();
    }

    public function logoutOfAllDevices(User $user)
    {
        $user->tokens()->delete();
    }

    public function sendForgotPasswordEmail(?User $user)
    {
        if($user == null)return;
        $token = Password::createToken($user);
        Mail::to($user)->queue(new NewUserPasswordChange($user, "$token?email=$user->email"));
    }

    public function sendForgotPasswordEmailWithEmail(string $email)
    {
        /** @var User $user */
        $user = $this->userModel->query()->where('email',$email)->first();
        $token = Password::createToken($user);
        Mail::to($user)->queue(new ForgotPasswordMail($user, "$token?email=$user->email"));
    }

    public function resetPassword(array $payload): bool
    {
        ['token' => $token, 'email' => $email, 'password' => $password] = $payload;

        $status = Password::reset(
            [
                'email' => $email,
                'token' => $token,
                'password' => $password,
                'password_confirmation' => $password,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);
                $user->save();

                //TODO send welcome emails here
            }
        );

        return $status === Password::PASSWORD_RESET;
        // TODO: Implement resetPassword() method.
    }


    public function getAuthUserProfile(User $user): array
    {
        return [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'status' => $user->status,
            'email_verified' => $user->email_verified_at != null,
            'phone_verified' => $user->phone_verified_at != null,
            'joined' => $user->created_at,
        ];
    }
}
