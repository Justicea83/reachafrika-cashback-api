<?php

namespace App\Services\Merchant\Pos;

use App\Dtos\Pos\PosApprovalDto;
use App\Entities\Responses\Pos\DashboardStats;
use App\Models\Finance\Transaction;
use App\Models\Merchant\ApprovalRequest;
use App\Models\Merchant\Merchant;
use App\Models\Merchant\Pos;
use App\Models\Merchant\PosApproval;
use App\Models\User;
use App\Utils\Core\BaseCoreService;
use App\Utils\Core\Endpoints;
use App\Utils\Finance\Merchant\TransactionUtils;
use App\Utils\General\FilterOptions;
use App\Utils\MerchantUtils;
use App\Utils\PosApprovalUtils;
use App\Utils\Status;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class PosService implements IPosService
{
    private Pos $posModel;
    private ApprovalRequest $approvalRequestModel;
    private PosApproval $posApprovalModel;
    private Transaction $transactionModel;

    function __construct(
        Pos             $posModel,
        ApprovalRequest $approvalRequestModel,
        PosApproval     $posApprovalModel,
        Transaction     $transactionModel
    )
    {
        $this->posModel = $posModel;
        $this->approvalRequestModel = $approvalRequestModel;
        $this->posApprovalModel = $posApprovalModel;
        $this->transactionModel = $transactionModel;
    }

    /**
     * @throws Exception
     */
    public function createPos(User $user, array $payload): Model
    {
        if ($user->merchant_id == null) throw new InvalidArgumentException("you cannot create a pos because you don't have a merchant");

        if ($this->posModel->query()->where('user_id', $user->id)->count() > 1) throw new InvalidArgumentException("this user is already assigned to POS");

        $payload['created_by'] = $user->id;
        $payload['merchant_id'] = $user->merchant_id;
        $payload['code'] = $this->getCodeForPos();

        /** @var Pos $pos */
        $pos = $this->posModel->query()->create($payload);
        return $this->getPos($pos->id);
    }

    /**
     * @throws Exception
     */
    private function getCodeForPos(): string
    {
        do {
            $code = random_int(1000, 99999999);
        } while ($this->posModel->query()->where("code", $code)->first());

        return (string)$code;
    }

    public function getMerchantBranchPosByBranchId(User $user, int $branchId): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        return $this->posModel->query()
            ->where('merchant_id', $user->merchant_id)
            ->where('branch_id', $branchId)
            ->paginate($pageSize, ['*'], 'page', $page);
    }

    public function getAllPos(User $user): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        return $this->posModel->query()
            ->where('merchant_id', $user->merchant_id)
            ->paginate($pageSize, ['*'], 'page', $page);
    }

    public function getMerchantPosByMerchantId(User $user, int $merchantId): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        return $this->posModel->query()
            ->where('merchant_id', $merchantId)
            ->paginate($pageSize, ['*'], 'page', $page);
    }

    private function updatePosStatus(int $id, string $status)
    {
        /** @var Pos $pos */
        $pos = $this->getPos($id);
        if ($pos != null) {
            $pos->status = $status;
            $pos->save();
        }
    }

    public function markAsActive(int $id)
    {
        $this->updatePosStatus($id, MerchantUtils::MERCHANT_STATUS_ACTIVE);

    }

    public function markAsBlocked(int $id)
    {
        $this->updatePosStatus($id, MerchantUtils::MERCHANT_STATUS_SUSPENDED);

    }

    public function markAsPending(int $id)
    {
        $this->updatePosStatus($id, MerchantUtils::MERCHANT_STATUS_PENDING);
    }

    public function deletePos(int $id)
    {
        $this->posModel->query()->find($id)->delete();
    }

    public function getPos(int $posId): ?Model
    {
        return $this->posModel->query()->with(['user', 'branch'])->find($posId);
    }

    public function updatePos(User $user, int $id, array $payload)
    {
        $this->posModel->query()->find($id)->update([
            'name' => $payload['name']
        ]);
    }

    public function assignPosToUser(int $id, int $userId)
    {
        $this->posModel->query()->find($id)->update([
            'user_id' => $userId
        ]);
    }

    public function assignPosToBranch(int $id, int $branchId)
    {
        $this->posModel->query()->find($id)->update([
            'branch_id' => $branchId
        ]);
    }

    public function getArchivedPos(User $user): Collection
    {
        return $this->posModel->query()
            ->onlyTrashed()
            ->where('merchant_id', $user->merchant_id)
            ->get();
    }

    public function undelete(int $id): Model
    {
        $this->posModel->query()->withTrashed()->find($id)->restore();
        return $this->getPos($id);
    }

    public function sendApprovalRequest(User $user, array $payload, string $userAgent)
    {
        $pos = $user->pos;
        $country = $user->merchant->country;
        if ($pos == null) throw new InvalidArgumentException('this users has not been assigned a POS');
        if ($country == null) throw new InvalidArgumentException('this merchant needs to be assigned to a country');

        $this->approvalRequestModel->query()->create([
            'pos_id' => $pos->id,
            'phone' => $payload['phone'],
            'amount' => $payload['amount'],
            'currency' => $country->currency,
            'currency_symbol' => $country->currency_symbol,
            'created_by' => $user->id,
            'extra_info' => json_encode(
                [
                    'platform' => $userAgent
                ]
            )
        ]);
    }

    public function getQrCode(User $user, int $posId): string
    {
        /** @var Pos $pos */
        $pos = $this->posModel->query()->find($posId);
        if (is_null($pos)) throw new InvalidArgumentException("pos not found");
        return $this->genQrCode($user->merchant, $pos);
    }

    private function genQrCode(Merchant $merchant, Pos $pos): string
    {
        $this->makeDirectories();
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($pos->id)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(400)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->logoPath(public_path('images\mini.jpg'))
            // ->logoPath(asset('images/mini.jpg'))
            ->labelText($merchant->name)
            ->labelFont(new NotoSans(20))
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        $result->saveToFile(sprintf("%s\\%s.png", config('filesystems.disks.qr-codes.root'), $pos->id));

        return asset("storage/qr-codes/$pos->id.png");
    }

    private function makeDirectories()
    {
        if (!Storage::disk('local')->exists('public\\qr-codes')) {
            Storage::disk('local')->makeDirectory('public\\qr-codes');
        }
    }

    public function getMyApprovals(User $user, FilterOptions $filterOptions): LengthAwarePaginator
    {
        $pagedData = $this->posApprovalModel->query()
            ->where('pos_id', $user->pos->id)
            ->where('status', Status::STATUS_PENDING)
            ->latest()
            ->paginate($filterOptions->pageSize, ['*'], 'page', $filterOptions->page);

        $pagedData->getCollection()->transform(function (PosApproval $posApproval) {
            return PosApprovalDto::map($posApproval);
        });
        return $pagedData;
    }


    public function getMobileAppDashboardStats(User $user): DashboardStats
    {
        $merchant = $user->merchant;
        $pos = $user->pos;

        $currency = $merchant->country->currency;

        /*dd([
            now()->startOfDay()->unix(),
            now()->endOfDay()->unix(),
            now()->unix(),
        ]);*/

        $todaySales = $this->transactionModel->query()->where('merchant_id', $merchant->id)
            ->where('pos_id', $pos->id)
            ->where('transaction', TransactionUtils::TRANSACTION_CREDIT)
            // ->whereDate('created_at', now()->toDateString())
            ->whereBetween('created_at', [now()->startOfDay()->unix(), now()->endOfDay()->unix()])
            ->sum('amount');

        $totalWeekly = $this->transactionModel->query()
            ->where('merchant_id', $merchant->id)
            ->where('pos_id', $pos->id)
            ->where('transaction', TransactionUtils::TRANSACTION_CREDIT)
            ->whereBetween('created_at', [now()->startOfWeek()->unix(), now()->endOfWeek()->unix()])
            ->sum('amount');

        $totalMonthly = $this->transactionModel->query()
            ->where('merchant_id', $merchant->id)
            ->where('pos_id', $pos->id)
            ->where('transaction', TransactionUtils::TRANSACTION_CREDIT)
            ->whereBetween('created_at', [now()->startOfMonth()->unix(), now()->endOfMonth()->unix()])
            ->sum('amount');

        return DashboardStats::instance()->setMerchantName($merchant->name)
            ->setMyName($user->fullName)
            ->setNotificationCount(0)
            ->setPosCode($pos->code)
            ->setSalesThisMonth(self::formatAmountWithCurrency($currency, $totalMonthly))
            ->setSalesThisWeek(self::formatAmountWithCurrency($currency, $totalWeekly))
            ->setSalesToday(self::formatAmountWithCurrency($currency, $todaySales));
    }

    private static function formatAmountWithCurrency(string $currency, float $amount): string
    {
        return sprintf("%s %s", $currency, number_format($amount, 2));
    }


    /**
     * @throws Exception
     */
    public function approvalRequestActionCall(User $user, array $payload)
    {
        ['action' => $action, 'request_id' => $requestId] = $payload;

        /** @var PosApproval $posApproval */
        $posApproval = $this->posApprovalModel->query()->find($requestId);

        $response = BaseCoreService::makeCall(Endpoints::getEndpointForAction(Endpoints::POS_APPROVAL_ACTION_CALL_ENDPOINT), [
            'reference' => $posApproval->reference,
            'action' => $action
        ]);

        if ($response->successful()) {
            switch ($action) {
                case PosApprovalUtils::ACTION_APPROVE:
                    $posApproval->status = Status::STATUS_COMPLETED;
                    break;
                case PosApprovalUtils::ACTION_DENY:
                    $posApproval->status = Status::STATUS_REJECTED;
                    break;
            }
            $posApproval->save();
        }
        //TODO add a try count to the model so you can reject after a specific number of tries
    }
}
