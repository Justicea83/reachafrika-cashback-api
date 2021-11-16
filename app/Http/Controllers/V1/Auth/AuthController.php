<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\MobileLoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Services\Auth\IAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private IAuthService $authService;

    function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }
    //

    public function mobileLogin(MobileLoginRequest $request): JsonResponse
    {
        return $this->successResponse($this->authService->mobileAppLogin($request->all()));
    }

    public function getAuthenticatedUser(Request $request): JsonResponse
    {
        return $this->successResponse($this->authService->getAuthUserProfile($request->user()));
    }

    public function mobileLogout(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $this->authService->mobileLogout($user);
        return $this->noContent();
    }

    public function forgotPassword(ForgotPasswordRequest $request): Response
    {
        $this->authService->sendForgotPasswordEmailWithEmail($request->get('email'));
        return $this->noContent();
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        if($this->authService->resetPassword($request->all())){
            return $this->successResponse('password changed successfully');
        }
        return $this->errorResponse('we could not change your password');
    }

    public function logoutOfAllDevices(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $this->authService->logoutOfAllDevices($user);
        return $this->noContent();
    }
}
