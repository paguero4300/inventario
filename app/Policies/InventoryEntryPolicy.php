<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\InventoryEntry;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryEntryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:InventoryEntry');
    }

    public function view(AuthUser $authUser, InventoryEntry $inventoryEntry): bool
    {
        return $authUser->can('View:InventoryEntry');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:InventoryEntry');
    }

    public function update(AuthUser $authUser, InventoryEntry $inventoryEntry): bool
    {
        return $authUser->can('Update:InventoryEntry');
    }

    public function delete(AuthUser $authUser, InventoryEntry $inventoryEntry): bool
    {
        return $authUser->can('Delete:InventoryEntry');
    }

    public function restore(AuthUser $authUser, InventoryEntry $inventoryEntry): bool
    {
        return $authUser->can('Restore:InventoryEntry');
    }

    public function forceDelete(AuthUser $authUser, InventoryEntry $inventoryEntry): bool
    {
        return $authUser->can('ForceDelete:InventoryEntry');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:InventoryEntry');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:InventoryEntry');
    }

    public function replicate(AuthUser $authUser, InventoryEntry $inventoryEntry): bool
    {
        return $authUser->can('Replicate:InventoryEntry');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:InventoryEntry');
    }

}