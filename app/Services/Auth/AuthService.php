<?php


namespace App\Services\Auth;


use App\Mail\Auth\ForgotPasswordMail;
use App\Mail\Auth\NewUserPasswordChange;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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

        $response = Http::asForm()->post(route('passport.token'), [
            'grant_type' => 'password',
            'client_id' => config('env.auth.client_id'),
            'client_secret' => config('env.auth.client_secret'),
            'username' => $data['email'],
            'password' => $data['password'],
            'scope' => '',
        ]);
        $responseInfo = $response->json();
        if ($response->ok())
            $responseInfo['user'] = $this->getAuthUserProfile($user);
        return $responseInfo;
    }

    public function loginWithRefreshToken(array $data): array
    {
        $response = Http::asForm()->acceptJson()
            ->post(route('passport.token'), [
                'grant_type' => 'refresh_token',
                'refresh_token' => $data['refresh_token'],
                'client_id' => config('env.auth.client_id'),
                'client_secret' => config('env.auth.client_secret'),
                'scope' => '',
            ]);
        return $response->json();
    }

    public function mobileLogout(User $user)
    {
        $user->token()->revoke();
    }

    public function logoutOfAllDevices(User $user)
    {
        $user->tokens()->delete();
    }

    public function sendForgotPasswordEmail(?User $user)
    {
        if ($user == null) return;
        $token = Password::createToken($user);
        Mail::to($user)->queue(new NewUserPasswordChange($user, "$token?email=$user->email"));
    }

    public function sendForgotPasswordEmailWithEmail(string $email)
    {
        /** @var User $user */
        $user = $this->userModel->query()->where('email', $email)->first();
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
            'merchant_name' => $user->merchant->name,
            'id'=> $user->id
        ];
    }

    public function changePassword(User $user, string $password, bool $logout = false)
    {
        $user->password = bcrypt($password);
        if ($user->isDirty()) {
            $user->save();

            if ($logout) {
                $user->tokens()
                    ->where('id', '<>', $user->token()->id)
                    ->delete();
            }
        }
    }


}
