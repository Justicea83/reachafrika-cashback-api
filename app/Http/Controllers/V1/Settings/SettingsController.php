<?php

namespace App\Http\Controllers\V1\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\AccountSettings\ChangePasswordRequest;
use App\Http\Requests\Settings\BusinessInfo\BusinessInfoRequest;
use App\Http\Requests\Settings\SettlementBank\CreateSettlementBankPurposeRequest;
use App\Http\Requests\Settings\SettlementBank\CreateSettlementBankRequest;
use App\Http\Requests\Settings\SettlementBank\UpdateSettlementBankRequest;
use App\Services\Auth\IAuthService;
use App\Services\Settings\BusinessInfo\IBusinessInfoService;
use App\Services\Settings\SettlementBank\ISettlementBankService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    private IBusinessInfoService $businessInfoService;
    private ISettlementBankService $settlementBankService;
    private IAuthService $authService;

    function __construct(
        IBusinessInfoService   $businessInfoService,
        ISettlementBankService $settlementBankService,
        IAuthService           $authService
    )
    {
        $this->businessInfoService = $businessInfoService;
        $this->settlementBankService = $settlementBankService;
        $this->authService = $authService;
    }

    public function updatedBusinessInfo(BusinessInfoRequest $request): Response
    {
        $avatar = null;
        if ($request->hasFile('avatar'))
            $avatar = $request->file('avatar');
        $this->businessInfoService->updateMerchantBusinessInfo($request->user(), $request->all(), $avatar);
        return $this->noContent();
    }

    //settlement banks
    public function createSettlementBank(CreateSettlementBankRequest $request): JsonResponse
    {
        return $this->successResponse($this->settlementBankService->addSettlementBank($request->user(), $request->only(['bank_name', 'account_no', 'account_name', 'purpose'])), Response::HTTP_CREATED);
    }

    public function updateSettlementBank(UpdateSettlementBankRequest $request, int $id): Response
    {
        $this->settlementBankService->updateSettlementBank($request->user(), $id, $request->only(['bank_name', 'account_no', 'account_name']));
        return $this->noContent();
    }

    public function getSettlementBank(): JsonResponse
    {
        return $this->successResponse($this->settlementBankService->getSettlementBanks(request()->user()));
    }

    public function getSettlementBankPurposes(int $id): JsonResponse
    {
        return $this->successResponse($this->settlementBankService->getSettlementBankPurposes(request()->user(), $id));
    }

    public function removeSettlementBankPurpose(int $id, int $purposeId): Response
    {
        $this->settlementBankService->removePurposeFromSettlementBank(request()->user(), $id, $purposeId);
        return $this->noContent();
    }

    public function createSettlementBankPurpose(int $id, CreateSettlementBankPurposeRequest $request): JsonResponse
    {
        return $this->successResponse($this->settlementBankService->addPurposeToSettlementBank(request()->user(), $id, $request->only('purpose')));
    }

    public function changePassword(ChangePasswordRequest $request): Response
    {
        $this->authService->changePassword(request()->user(), $request['new_password']);
        return $this->noContent();
    }
}
