<?php

namespace App\Utils\Notifications\Fcm;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ServiceCallUtils
{
    const ACTION_CREATE = 'create';
    const ACTION_ADD = 'add';
    const ACTION_REMOVE = 'remove';

    public static function notificationTokenAction(string $action, string $userKeyName, array $tokens, ?string $userKey = null): Response
    {
        $secret = config('fcm.secret');
        $payload = [
            'operation' => $action,
            'notification_key_name' => $userKeyName,
            'registration_ids' => $tokens
        ];

        if ($action != self::ACTION_CREATE && $userKey != null) {
            $payload['notification_key'] = $userKey;
        }

        return Http::asJson()
            ->retry(3, 100)
            ->withHeaders([
                'Authorization' => "key=$secret",
                'project_id' => config('fcm.project_id')
            ])
            ->post(FcmEndpointUtils::NOTIFICATION_ENDPOINT, $payload);
    }
}
