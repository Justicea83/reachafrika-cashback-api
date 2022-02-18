<?php

namespace App\Http\Controllers\V1\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notifications\FcmRegisterRequest;
use App\Services\Notifications\Fcm\IFcmNotificationService;
use Symfony\Component\HttpFoundation\Response;

class NotificationsController extends Controller
{
    private IFcmNotificationService $fcmNotificationService;

    function __construct(IFcmNotificationService $fcmNotificationService)
    {
        $this->fcmNotificationService = $fcmNotificationService;
    }

    public function registerForFcmNotifications(FcmRegisterRequest $request): Response
    {
        $this->fcmNotificationService->register($request->user(),$request->get('token'),$request->header('api-user-agent'));
        return $this->noContent();
    }
}
