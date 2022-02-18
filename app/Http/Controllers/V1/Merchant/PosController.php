<?php

namespace App\Http\Controllers\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Merchant\Pos\AssignToBranchRequest;
use App\Http\Requests\Merchant\Pos\AssignToUserRequest;
use App\Http\Requests\Merchant\Pos\CreatePosRequest;
use App\Http\Requests\Merchant\Pos\PosApprovalActionRequest;
use App\Http\Requests\Merchant\Pos\SendApprovalRequest;
use App\Http\Requests\Merchant\Pos\UpdatePosRequest;
use App\Services\Merchant\Pos\IPosService;
use App\Utils\General\FilterOptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PosController extends Controller
{
    private IPosService $posService;

    function __construct(IPosService $merchantService)
    {
        $this->posService = $merchantService;
    }

    public function createBranchPos(CreatePosRequest $request): JsonResponse
    {
        return $this->successResponse($this->posService->createPos($request->user(), $request->all()));
    }

    public function getArchivedPos(Request $request): JsonResponse
    {
        return $this->successResponse($this->posService->getArchivedPos($request->user()));
    }

    public function undeletePos(int $id): JsonResponse
    {
        return $this->successResponse($this->posService->undelete($id));
    }


    public function assignPosToUser(AssignToUserRequest $request, int $id): Response
    {
        $this->posService->assignPosToUser($id, $request->get('user_id'));
        return $this->noContent();
    }

    public function assignPosToBranch(AssignToBranchRequest $request, int $id): Response
    {
        $this->posService->assignPosToBranch($id, $request->get('branch_id'));
        return $this->noContent();
    }

    public function updateBranchPos(UpdatePosRequest $request, int $id): Response
    {
        $this->posService->updatePos($request->user(), $id, $request->all());
        return $this->noContent();
    }

    public function getAllPos(Request $request): Response
    {
        return $this->successResponse($this->posService->getAllPos($request->user()));
    }

    public function getMerchantPos(Request $request, int $merchantId): Response
    {
        return $this->successResponse($this->posService->getMerchantPosByMerchantId($request->user(), $merchantId));
    }

    public function getBranchPos(Request $request, int $branchId): Response
    {
        return $this->successResponse($this->posService->getMerchantBranchPosByBranchId($request->user(), $branchId));
    }

    public function getPos(int $id): Response
    {
        return $this->successResponse($this->posService->getPos($id));
    }

    public function getMobileAppDashboardStats(): Response
    {
        return $this->successResponse($this->posService->getMobileAppDashboardStats(\request()->user()));
    }

    public function markAsBlocked(int $id): Response
    {
        $this->posService->markAsBlocked($id);
        return $this->noContent();
    }

    public function markAsActive(int $id): Response
    {
        $this->posService->markAsActive($id);
        return $this->noContent();
    }

    public function markAsPending(int $id): Response
    {
        $this->posService->markAsPending($id);
        return $this->noContent();
    }

    public function deletePos(int $id): Response
    {
        $this->posService->deletePos($id);
        return $this->noContent();
    }

    public function approvalRequestActionCall(PosApprovalActionRequest $request): Response
    {
        $this->posService->approvalRequestActionCall($request->user(),$request->all());
        return $this->noContent();
    }

    public function sendApprovalRequest(SendApprovalRequest $request): Response
    {
        $this->posService->sendApprovalRequest($request->user(), $request->only(['phone', 'amount']), $request->header('Api-User-Agent'));
        return $this->noContent();
    }

    public function generateQrCode(int $posId): JsonResponse
    {
        return $this->successResponse($this->posService->getQrCode(request()->user(), $posId));
    }

    public function getMyApprovals(): JsonResponse
    {
        return $this->successResponse($this->posService->getMyApprovals(request()->user(),
            new FilterOptions(request()->query('page') ?? 1, request()->query('page-size') ?? 25, request()->query('search-query'))
        ));
    }
}
