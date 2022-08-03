<?php

namespace App\Policies;

use App\Models\Storage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoragePolicy
{
    use HandlesAuthorization;

    private function canAccess(User $user, Storage $storage): bool
    {
        return $user->is_admin || $user->has_storage && $storage->user_id === $user->id;
    }

    public function viewAny(User $user): bool
    {
        return $user->is_admin || $user->has_storage;
    }

    public function view(User $user, Storage $storage): bool
    {
        return $this->canAccess($user, $storage);
    }

    public function create(User $user): bool
    {
        return $user->is_admin || $user->has_storage;
    }

    public function update(User $user, Storage $storage): bool
    {
        return $this->canAccess($user, $storage);
    }

    public function delete(User $user, Storage $storage): bool
    {
        return $this->canAccess($user, $storage);
    }

    public function forceDelete(User $user, Storage $storage): bool
    {
        return $this->canAccess($user, $storage);
    }
}
