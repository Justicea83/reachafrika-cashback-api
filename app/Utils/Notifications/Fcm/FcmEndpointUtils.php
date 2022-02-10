<?php

namespace App\Utils\Notifications\Fcm;

class FcmEndpointUtils
{
    const BASEURL = "https://fcm.googleapis.com";
    const FCM_ENDPOINT = self::BASEURL . "/fcm";
    const NOTIFICATION_ENDPOINT = self::FCM_ENDPOINT . "/notification";
}
