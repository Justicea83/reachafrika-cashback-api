<?php

namespace App\Dtos\Pos;

use App\Dtos\BaseDto;
use App\Models\Merchant\Merchant;
use App\Models\Merchant\PosApproval;
use App\Models\User;
use App\Services\Merchant\IMerchantService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;

class PosApprovalDto extends BaseDto
{

    public int $id;
    public string $recipient_phone;
    public string $recipient_name;
    public float $amount;
    public string $formatted_amount;
    public string $status;
    public string $description;
    public string $payment_mode;
    public string $date;

    /**
     * @param PosApproval $model
     * @param array $params
     */
    public function mapFromModel($model, array $params = []): PosApprovalDto
    {
        /** @var User $user */
        $user = request()->user();
        /** @var IMerchantService $merchantDetails */
        $merchantDetails = App::make(IMerchantService::class);
        /** @var Merchant $merchant */
        $merchant = $merchantDetails->getMerchant((int)$user->merchant_id);

        if (is_null($merchant)) throw new InvalidArgumentException("merchant cannot be found");

        $paymentMode = $model->paymentMode;

        $instance = self::instance();
        $instance->id = $model->id;
        $instance->amount = $model->amount_due;
        $instance->date = Carbon::parse($model->created_at)->diffForHumans();
        $instance->formatted_amount = sprintf("%s %s", $merchant->country->currency_symbol, number_format($model->amount_due, 2));
        $instance->recipient_name = $model->recipient_name;
        $instance->recipient_phone = $model->recipient_phone;
        $instance->payment_mode = $paymentMode->display_name;
        $instance->description = sprintf("%s with %s is making a payment of %s %s",
            $model->recipient_name,
            $model->recipient_phone,
            $merchant->country->currency_symbol,
            number_format($model->amount_due, 2)
        );

        return $instance;
    }

    private static function instance(): PosApprovalDto
    {
        return new PosApprovalDto();
    }

    public static function map(PosApproval $posApproval): PosApprovalDto
    {
        $instance = self::instance();
        return $instance->mapFromModel($posApproval);
    }
}
