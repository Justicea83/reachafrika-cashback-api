<?php


namespace App\Services\UserManagement;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IUserManagementService
{
    public function createRole(User $user,array $payload) : Model;
    public function getRoles(User $user) : Collection;
    public function getRolesForUser(User $user,int $id) : Collection;
    public function updateRole(User $user,array $payload,int $id);
    public function deleteRole(User $user,int $id);
    public function assignRoleToUserByRoleName(int $userId,string $roleName);
    public function assignRoleToUserByRoleId(int $userId,int $roleId);
    public function assignPermissionsToRole(User $user, array $payload);
    public function getUserRoles(User $user) : Collection;
    public function getUserPermissions(User $user) : Collection;
    public function getAllPermissions(User $user) : Collection;
    public function assignPermissionToUser(int $userId,string $permissionName);
    public function createUser(array $payload, bool $resetPassword = false) : Model;
    //TODO endpoint to assign permission to role
    //TODO routes for users
    public function getUsers(User $user) : LengthAwarePaginator;
    public function getUserById(User $user,int $id) : Model;
    public function updateUser(User $user,int $id,array $payload);
    public function suspendUser(User $user,int $userId, array $payload);
}
