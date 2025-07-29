<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    public function __construct(
        protected User $user,
        protected Role $role
    ) {}

    public function createUser(array $userData, array $roles = []): User
    {
        return DB::transaction(function () use ($userData, $roles) {
            $user = $this->user->create($this->prepareUserData($userData));

            if (! empty($roles)) {
                $user->syncRoles($roles);
            }

            return $user->load('roles');
        });
    }

    public function updateUser(User $user, array $userData, array $roles = []): User
    {
        return DB::transaction(function () use ($user, $userData, $roles) {
            $user->update($this->prepareUserData($userData));

            if (! empty($roles)) {
                $user->syncRoles($roles);
            }

            return $user->fresh('roles');
        });
    }

    public function deleteUser(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            $user->roles()->detach();

            return $user->delete();
        });
    }

    public function getUsersWithRoles(int $perPage = 10): LengthAwarePaginator
    {
        return $this->user->with('roles')
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function getAllRoles(): Collection
    {
        return $this->role->orderBy('name')->get();
    }

    public function findUserById(int $userId): User
    {
        return $this->user->with('roles')->findOrFail($userId);
    }

    public function searchUsers(string $query, int $perPage = 10): LengthAwarePaginator
    {
        return $this->user->with('roles')
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', "%{$query}%")
                    ->orWhere('username', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->latest('created_at')
            ->paginate($perPage);
    }

    private function prepareUserData(array $userData): array
    {
        if (isset($userData['password']) && ! empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        return $userData;
    }
}
