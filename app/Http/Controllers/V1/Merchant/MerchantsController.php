<?php

namespace App\Http\Controllers\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CreateUserRequest;
use App\Http\Requests\Merchant\CreateMerchantBranchRequest;
use App\Http\Requests\Merchant\CreateMerchantRequest;
use App\Http\Requests\Merchant\UpdateMerchantRequest;
use App\Services\Merchant\IMerchantService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MerchantsController extends Controller
{
    private IMerchantService $merchantService;

    function __construct(IMerchantService $merchantService)
    {
        $this->merchantService = $merchantService;
    }

    public function setup(CreateMerchantRequest $request): Response
    {
        $this->merchantService->setup($request->user(), $request->all());
        return $this->noContent();
    }

    public function getMerchants(): JsonResponse
    {
        return $this->successResponse($this->merchantService->getMerchants());
    }

    public function getMerchantsByStatus(string $status): JsonResponse
    {
        return $this->successResponse($this->merchantService->getMerchantsByStatus($status));
    }

    public function getMerchant(int $id): JsonResponse
    {
        return $this->successResponse($this->merchantService->getMerchant($id));
    }

    public function updateMerchant(UpdateMerchantRequest $request, int $id): Response
    {
        $this->merchantService->updateMerchant($request->user(),$request->all(),$id);
        return $this->noContent();
    }

    public function getMerchantBranches(int $id): JsonResponse
    {
        return $this->successResponse($this->merchantService->getMerchantBranches($id));
    }

    public function getMerchantUsers(int $id): JsonResponse
    {
        return $this->successResponse($this->merchantService->getMerchantUsers($id));
    }

    public function createMerchantBranch(CreateMerchantBranchRequest $request, int $id): JsonResponse
    {
        return $this->successResponse($this->merchantService->createMerchantBranchByMerchantId($request->user(), $id, $request->all()));
    }

    public function createMerchantUser(CreateUserRequest $request, int $id): JsonResponse
    {
        return $this->successResponse($this->merchantService->createMerchantUserByMerchantId($request->user(), $id, $request->all()));
    }

    public function markAsBlocked(int $id): Response
    {
        $this->merchantService->markAsBlocked($id);
        return $this->noContent();
    }

    public function markAsActive(int $id): Response
    {
        $this->merchantService->markAsActive($id);
        return $this->noContent();
    }

    public function markAsPending(int $id): Response
    {
        $this->merchantService->markAsPending($id);
        return $this->noContent();
    }

    public function deleteMerchant(int $id): Response
    {
        $this->merchantService->deleteMerchant($id);
        return $this->noContent();
    }


}
