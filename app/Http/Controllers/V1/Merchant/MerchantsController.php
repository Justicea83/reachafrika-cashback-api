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

    public function getMerchantBranches(): JsonResponse
    {
        return $this->successResponse($this->merchantService->getMerchantBranches(request()->user()));
    }

    public function getMerchantUsers(): JsonResponse
    {
        return $this->successResponse($this->merchantService->getMerchantUsers(request()->user()));
    }

    public function createMerchantBranch(CreateMerchantBranchRequest $request): JsonResponse
    {
        return $this->successResponse($this->merchantService->createMerchantBranchByMerchantId($request->user(),  $request->all()));
    }

    public function createMerchantUser(CreateUserRequest $request): JsonResponse
    {
        return $this->successResponse($this->merchantService->createMerchantUserByMerchantId($request->user(), $request->all()));
    }

    public function markAsBlocked(): Response
    {
        $this->merchantService->markAsBlocked(request()->user());
        return $this->noContent();
    }

    public function markAsActive(): Response
    {
        $this->merchantService->markAsActive(request()->user());
        return $this->noContent();
    }

    public function markAsPending(): Response
    {
        $this->merchantService->markAsPending(request()->user());
        return $this->noContent();
    }

    public function deleteMerchant(): Response
    {
        $this->merchantService->deleteMerchant(request()->user());
        return $this->noContent();
    }

    public function deleteBranch(int $branchId): Response
    {
        $this->merchantService->deleteBranch(request()->user(),$branchId);
        return $this->noContent();
    }

    public function changeBranchStatus(int $branchId, string $status): Response
    {
        $this->merchantService->changeBranchStatus(request()->user(),$branchId,$status);
        return $this->noContent();
    }

}
