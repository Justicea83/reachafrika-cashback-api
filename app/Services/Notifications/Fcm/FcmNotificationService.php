<?php

namespace App\Services\Notifications\Fcm;

use App\Models\Notifications\FcmToken;
use App\Models\User;
use App\Utils\General\MiscUtils;
use App\Utils\Notifications\Fcm\ServiceCallUtils;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class FcmNotificationService implements IFcmNotificationService
{

    private FcmToken $fcmTokenModel;
    private User $userModel;

    function __construct(FcmToken $fcmTokenModel, User $userModel)
    {
        $this->fcmTokenModel = $fcmTokenModel;
        $this->userModel = $userModel;
    }

    /**
     * @throws Throwable
     */
    public function register(User $user, string $token, string $device)
    {
        DB::beginTransaction();
        try {
            $fcmToken = $user->notificationTokens()->where('token', $token)->first();

            if ($fcmToken != null) {
                $fcmToken->touch();
            } else {
                $user->notificationTokens()->create([
                    'token' => $token,
                    'device' => $device
                ]);
                $this->addTokenToDeviceGroup($user, $token);
            }
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

    }

    public function addTokenToDeviceGroup(User $user, string $token)
    {
        $userUniqueName = sprintf("cashback-%s-%s", Str::slug($user->fullName), MiscUtils::getToken(30));

        $deviceGroup = $user->deviceGroup;

        if (is_null($deviceGroup)) {
            $response = ServiceCallUtils::notificationTokenAction(ServiceCallUtils::ACTION_CREATE, $userUniqueName, [$token]);
        } else {
            $response = ServiceCallUtils::notificationTokenAction(ServiceCallUtils::ACTION_ADD, $deviceGroup->notification_key_name, [$token], $deviceGroup->notification_key);
        }


        if ($response->successful()) {
            $data = $response->json();
            if (is_null($deviceGroup))
                $user->deviceGroup()->create([
                    'notification_key_name' => $userUniqueName,
                    'notification_key' => $data['notification_key']
                ]);
            Log::info(get_class(), $data);
        } else {
            Log::error(get_class(), ['message' => $response->body()]);
        }
    }

    public function pruneNotificationTokens()
    {
        $twoMonthsAgo = now()->addMonths(2)->unix();

        $this->fcmTokenModel->query()->where('updated_at', '<', $twoMonthsAgo)->select('token', 'id', 'user_id')->chunkById(100, function (Collection $tokens) {
            $groupedData = $tokens->groupBy('user_id');
            /** @var Collection $groupedTokens */
            foreach ($groupedData as $userId => $groupedTokens) {
                $ids = $groupedTokens->pluck('id')->toArray();
                $fcmTokens = $groupedTokens->pluck('token')->toArray();

                /** @var User $user */
                $user = $this->userModel->query()->find($userId);

                if (is_null($user)) {
                    DB::table('fcm_tokens')->whereIn('id', $ids)->delete();
                    continue;
                }

                $deviceGroup = $user->deviceGroup;

                if (is_null($deviceGroup)) continue;

                $response = ServiceCallUtils::notificationTokenAction(ServiceCallUtils::ACTION_REMOVE, $deviceGroup->notification_key_name, $fcmTokens, $deviceGroup->notification_key);

                if ($response->successful()) {
                    Log::info(get_class(), $response->json());
                    DB::table('fcm_tokens')->whereIn('id', $ids)->delete();
                    if ($user->notificationTokens()->count() <= 0) {
                        $deviceGroup->forceDelete();
                    }
                }

            }
        });
    }



}
