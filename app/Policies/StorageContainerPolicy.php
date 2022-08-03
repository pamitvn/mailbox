<?php

namespace App\Policies;

use App\Models\StorageContainer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorageContainerPolicy
{
    use HandlesAuthorization;

    private function canAccess(User $user, StorageContainer $storageContainer): bool
    {
        return $user->is_admin || $user->has_storage && $storageContainer->parent->user_id === $user->id;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, StorageContainer $storageContainer): bool
    {
        return $this->canAccess($user, $storageContainer);
    }

    public function create(User $user): bool
    {
        return $user->is_admin || $user->has_storage;
    }

    public function update(User $user, StorageContainer $storageContainer): bool
    {
        return $this->canAccess($user, $storageContainer);
    }

    public function delete(User $user, StorageContainer $storageContainer): bool
    {
        return $this->canAccess($user, $storageContainer);
    }
}
