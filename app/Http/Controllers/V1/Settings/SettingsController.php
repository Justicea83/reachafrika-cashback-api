<?php

namespace App\Http\Controllers\V1\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\BusinessInfo\BusinessInfoRequest;
use App\Http\Requests\Settings\SettlementBank\CreateSettlementBankRequest;
use App\Services\Settings\BusinessInfo\IBusinessInfoService;
use App\Services\Settings\SettlementBank\ISettlementBankService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    private IBusinessInfoService $businessInfoService;
    private ISettlementBankService $settlementBankService;

    function __construct(IBusinessInfoService $businessInfoService,ISettlementBankService $settlementBankService){
        $this->businessInfoService = $businessInfoService;
        $this->settlementBankService = $settlementBankService;
    }
    public function updatedBusinessInfo(BusinessInfoRequest $request): Response
    {
        $avatar = null;
        if ($request->hasFile('avatar'))
            $avatar = $request->file('avatar');
        $this->businessInfoService->updateMerchantBusinessInfo($request->user(),$request->all(),$avatar);
        return $this->noContent();
    }

    //settlement banks
    public function createSettlementBank(CreateSettlementBankRequest $request): JsonResponse
    {
        return $this->successResponse($this->settlementBankService->addSettlementBank($request->user(),$request->only(['bank_name','account_no','account_name'])), Response::HTTP_CREATED);
    }

    public function getSettlementBank(): JsonResponse
    {
        return $this->successResponse($this->settlementBankService->getSettlementBank(request()->user()));
    }
}
