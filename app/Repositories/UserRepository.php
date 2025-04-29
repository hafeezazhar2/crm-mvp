<?php

namespace App\Repositories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function paginateAdvisors(int $perPage = 15): LengthAwarePaginator
    {
        return User::role(UserRole::ADVISOR->value)
            ->latest()
            ->paginate($perPage);
    }

    public function createAdvisor(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole(UserRole::ADVISOR->value);

        return $user;
    }

    public function updateAdvisor(User $user, array $data): User
    {
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        return $user->fresh();
    }

    public function deleteAdvisor(User $user): void
    {
        $user->delete();
    }
}
