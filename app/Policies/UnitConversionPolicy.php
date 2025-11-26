<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\UnitConversion;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnitConversionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UnitConversion');
    }

    public function view(AuthUser $authUser, UnitConversion $unitConversion): bool
    {
        return $authUser->can('View:UnitConversion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UnitConversion');
    }

    public function update(AuthUser $authUser, UnitConversion $unitConversion): bool
    {
        return $authUser->can('Update:UnitConversion');
    }

    public function delete(AuthUser $authUser, UnitConversion $unitConversion): bool
    {
        return $authUser->can('Delete:UnitConversion');
    }

    public function restore(AuthUser $authUser, UnitConversion $unitConversion): bool
    {
        return $authUser->can('Restore:UnitConversion');
    }

    public function forceDelete(AuthUser $authUser, UnitConversion $unitConversion): bool
    {
        return $authUser->can('ForceDelete:UnitConversion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UnitConversion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UnitConversion');
    }

    public function replicate(AuthUser $authUser, UnitConversion $unitConversion): bool
    {
        return $authUser->can('Replicate:UnitConversion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UnitConversion');
    }

}