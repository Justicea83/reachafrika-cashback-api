<?php


namespace App\Services\UserManagement;


use App\Models\Role;
use App\Models\User;
use App\Services\Auth\IAuthService;
use App\Utils\TenantUtils;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class UserManagementService implements IUserManagementService
{

    private User $userModel;
    private Role $roleModel;
    private IAuthService $authService;

    function __construct(User $userModel, Role $roleModel, IAuthService $authService)
    {
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
        $this->authService = $authService;
    }

    public function assignRoleToUserByRoleName(int $userId, string $roleName)
    {
        $this->findUser($userId)->assignRole($roleName);
    }

    public function assignRoleToUserByRoleId(int $userId, int $roleId)
    {
        $this->findUser($userId)->assignRole($roleId);
    }

    public function getUserRoles(User $user): Collection
    {
        return $user->getRoleNames();
    }

    private function findUser(int $id) : User{
        /** @var User $user */
        $user = $this->userModel->query()->find($id);
        if($user == null) throw new InvalidArgumentException("user not found");
        return $user;
    }

    public function assignPermissionToUser(int $userId, string $permissionName)
    {
        $this->findUser($userId)->givePermissionTo($permissionName);
    }

    public function getUserPermissions(User $user): Collection
    {
        return $user->getPermissionNames();
    }

    public function createUser(array $payload, bool $resetPassword = false): Model
    {
        $forceSendPasswordResetEmail = !isset($payload['password']);
        $payload['password'] = !$forceSendPasswordResetEmail ? bcrypt($payload['password']) : bcrypt('password123@');
        /** @var User $user */
        $user = $this->userModel->query()->create($payload);
        if ($resetPassword || $forceSendPasswordResetEmail) {
            $this->authService->sendForgotPasswordEmail($user);
        }
        return $user;
    }

    public function createRole(User $user, array $payload): Model
    {
        if ($user->merchant_id == null) throw new InvalidArgumentException("you cant create a role");
        $payload['merchant_id'] = $user->merchant_id;
        $payload['guard'] = 'api';
        return $this->roleModel->query()->create($payload);
    }

    public function getRoles(User $user): Collection
    {
        return $this->roleModel->query()
            ->where('merchant_id', $user->merchant_id)
            ->get();
    }

    public function getRolesForUser(User $user, int $id): Collection
    {
        $userInQuestion = $this->findUser($id);
        return $userInQuestion->roles()->get();
    }

    public function updateRole(User $user, array $payload, int $id)
    {
        $this->roleModel->query()->where('merchant_id', $user->merchant_id)
            ->where('id', $id)
            ->update([
                'name' => $payload['name']
            ]);
    }

    public function deleteRole(User $user, int $id)
    {
        $role = $this->roleModel->query()->find($id);
        if ($role != null)
            $role->delete();
    }

    public function getUsers(User $user): LengthAwarePaginator
    {
        $pageSize = request()->query->get('page-size') ?? 20;
        $page = request()->query->get('page') ?? 1;
        return $this->userModel->query()
            ->with(['roles'])
            ->where('merchant_id',$user->merchant_id)
            ->paginate($pageSize, ['*'], 'page', $page);
    }

    public function getUserById(User $user, int $id): Model
    {
        return $this->userModel->query()
            ->with(['roles'])
            ->where('merchant_id',$user->merchant_id)
            ->where('id',$id)
            ->first();
    }

    public function updateUser(User $user, int $id, array $payload)
    {
        $user = $this->findUser($id);
        //update only the first name and last name fields
        $firstName = Arr::get($payload,'first_name');
        $lastName = Arr::get($payload,'last_name');
        if($firstName != null)$user->first_name = $firstName;
        if($lastName != null)$user->last_name = $lastName;
        if($user->isDirty())$user->save();
    }

    public function suspendUser(User $user, int $userId, array $payload)
    {
        $user = $this->findUser($userId);
        $suspendUntil = Carbon::parse($payload['suspended_until']);
        $user->suspended_until = $suspendUntil->timestamp;
        $user->save();
    }
}
