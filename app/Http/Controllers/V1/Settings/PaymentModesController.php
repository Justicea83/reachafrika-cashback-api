<?php

namespace App\Http\Controllers\V1\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Finance\PaymentMode\CreatePaymentModeRequest;
use App\Services\Settings\Finance\PaymentMode\IPaymentModeService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PaymentModesController extends Controller
{
    private IPaymentModeService $paymentModeService;

    function __construct(IPaymentModeService $paymentModeService)
    {
        $this->paymentModeService = $paymentModeService;
    }

    public function addPaymentMode(CreatePaymentModeRequest $request): JsonResponse
    {
        return $this->successResponse($this->paymentModeService->addPaymentMode($request->user(), $request->only(['active','payment_mode_id'])));
    }

    public function getPaymentModes(): JsonResponse
    {
        return $this->successResponse($this->paymentModeService->getPaymentModes(request()->user()));
    }

    public function getAllPaymentModes(): JsonResponse
    {
        return $this->successResponse($this->paymentModeService->getAllPaymentModes(request()->user()));
    }

    public function toggleActive(int $paymentModeId): Response
    {
        $this->paymentModeService->toggleActive(request()->user(),$paymentModeId);
        return $this->noContent();
    }

    public function removePaymentMethod( int $paymentModeId): Response
    {
        $this->paymentModeService->removePaymentMethod(request()->user(),$paymentModeId);
        return $this->noContent();
    }
}
