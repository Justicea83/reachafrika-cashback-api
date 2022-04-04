<?php


namespace App\Http\Controllers\V1\UserManagement;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SuspendUserRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Requests\UserManagement\CreateRoleRequest;
use App\Services\UserManagement\IUserManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserManagementController extends Controller
{
    private IUserManagementService $userMgmtService;


    function __construct(IUserManagementService $userMgmtService)
    {
        $this->userMgmtService = $userMgmtService;
    }

    public function createRole(CreateRoleRequest $request): JsonResponse
    {
        return $this->successResponse($this->userMgmtService->createRole($request->user(),$request->only(['name','display_name'])));
    }

    public function getRoles(): JsonResponse
    {
        return $this->successResponse($this->userMgmtService->getRoles(\request()->user()));
    }

    public function assignPermissionsToRole(): Response
    {
        $this->userMgmtService->assignPermissionsToRole(request()->user(),request()->all());
        return $this->noContent();
    }

    public function getRolesForUser(int $userId): JsonResponse
    {
        return $this->successResponse($this->userMgmtService->getRolesForUser(\request()->user(),$userId));
    }

    public function updateRole(CreateRoleRequest $request,int $id): Response
    {
        $this->userMgmtService->updateRole(\request()->user(),$request->all(),$id);
        return $this->noContent();
    }

    public function deleteRole(int $id): Response
    {
        $this->userMgmtService->deleteRole(\request()->user(),$id);
        return $this->noContent();
    }

    public function assignPermissionToUser(int $userId,string $name): Response
    {
        $this->userMgmtService->assignPermissionToUser($userId,$name);
        return $this->noContent();
    }

    public function getUserPermissions(Request $request): JsonResponse
    {
        return $this->successResponse($this->userMgmtService->getUserPermissions($request->user()));
    }

    public function getAllPermissions(): JsonResponse
    {
        return $this->successResponse($this->userMgmtService->getAllPermissions(request()->user()));
    }

    public function getUserRoles(Request $request): JsonResponse
    {
        return $this->successResponse($this->userMgmtService->getUserRoles($request->user()));
    }

    public function assignRoleToUserByRoleId(int $userId,$id): Response
    {
        $this->userMgmtService->assignRoleToUserByRoleId($userId,$id);
        return $this->noContent();
    }

    public function assignRoleToUserByRoleName(int $userId,string $name): Response
    {
        $this->userMgmtService->assignRoleToUserByRoleName($userId,$name);
        return $this->noContent();
    }

    //users
    public function createUser(CreateRoleRequest $request): JsonResponse
    {
        $payload = $request->all();
        $payload['merchant_id'] = $request->user()->merchant_id;
        return $this->successResponse($this->userMgmtService->createUser($payload));
    }

    public function getUsers(): JsonResponse
    {
        return $this->successResponse($this->userMgmtService->getUsers(request()->user()));
    }

    public function getUserById(int $id): JsonResponse
    {
        return $this->successResponse($this->userMgmtService->getUserById(request()->user(),$id));
    }

    public function updateUser(UpdateUserRequest $request,int $id): Response
    {
        $this->userMgmtService->updateUser($request->user(),$id,$request->only(['first_name','last_name']));
        return $this->noContent();
    }

    public function suspendUser(SuspendUserRequest $request,int $id): Response
    {
        $this->userMgmtService->suspendUser($request->user(),$id,$request->only(['suspended_until']));
        return $this->noContent();
    }
}
